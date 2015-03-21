<?php
/**
 * Module configuration file reader
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Module\Dir;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Config\FileIterator;
use Magento\Framework\Config\FileIteratorFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\Read;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\ModuleListInterface;

class Reader
{
    /**
     * Module directories that were set explicitly
     *
     * @var array
     */
    protected $customModuleDirs = [];

    /**
     * Directory registry
     *
     * @var Dir
     */
    protected $moduleDirs;

    /**
     * Modules configuration provider
     *
     * @var ModuleListInterface
     */
    protected $modulesList;

    /**
     * @var Read
     */
    protected $modulesDirectory;

    /**
     * @var FileIteratorFactory
     */
    protected $fileIteratorFactory;

    /**
     * @param Dir $moduleDirs
     * @param ModuleListInterface $moduleList
     * @param Filesystem $filesystem
     * @param FileIteratorFactory $fileIteratorFactory
     */
    public function __construct(
        Dir $moduleDirs,
        ModuleListInterface $moduleList,
        Filesystem $filesystem,
        FileIteratorFactory $fileIteratorFactory,
        \Magento\Framework\Stdlib\String $string
    ) {
        $this->moduleDirs = $moduleDirs;
        $this->modulesList = $moduleList;
        $this->fileIteratorFactory = $fileIteratorFactory;
        $this->modulesDirectory = $filesystem->getDirectoryRead(DirectoryList::MODULES);
        $this->string = $string;
    }

    /**
     * Go through all modules and find configuration files of active modules
     *
     * @param string $filename
     * @return FileIterator
     */
    public function getConfigurationFiles($filename)
    {
        $result = [];
        foreach ($this->modulesList->getNames() as $moduleName) {

            $dir = $this->getModuleDir('etc', $moduleName);
            $paths = $this->modulesDirectory->search($filename, $dir);
            $result = array_merge($result, $paths);

//            $file = $this->getModuleDir('etc', $moduleName) . '/' . $filename;
//            $path = $this->modulesDirectory->getRelativePath($file);
//            if ($this->modulesDirectory->isExist($path)) {
//                $result[] = $path;
//            }
        }
        return $this->fileIteratorFactory->create($this->modulesDirectory, $result);
    }


    public function getViewFilePaths($filename, $area = 'base')
    {
        $result = [];
        foreach ($this->modulesList->getNames() as $moduleName) {

            $dir = $this->getModuleDir('view', $moduleName);
            $search = $area . '/' . $filename;
            $paths = $this->modulesDirectory->search($search, $dir);
            $map = [];
            foreach ($paths as $path) {
                $map[$path] = $moduleName;
            }
            $result = array_merge($result, $map);
        }
        // TODO: Needs to return the path and module name $path => $moduleName so callers can identify which module it came from.
        return $result;
    }

    /**
     * Go through all modules and find composer.json files of active modules
     *
     * @return FileIterator
     */
    public function getComposerJsonFiles()
    {
        $result = [];
        foreach ($this->modulesList->getNames() as $moduleName) {
            $file = $this->getModuleDir('', $moduleName) . '/composer.json';
            $path = $this->modulesDirectory->getRelativePath($file);
            if ($this->modulesDirectory->isExist($path)) {
                $result[] = $path;
            }
        }
        return $this->fileIteratorFactory->create($this->modulesDirectory, $result);
    }

    /**
     * Retrieve list of module action files
     *
     * @return array
     */
    public function getActionFiles()
    {
        $actions = [];
        foreach ($this->modulesList->getNames() as $moduleName) {
            $actionDir = $this->getModuleDir('Controller', $moduleName);
            if (!file_exists($actionDir)) {
                continue;
            }
            $dirIterator = new \RecursiveDirectoryIterator($actionDir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $recursiveIterator = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::LEAVES_ONLY);
            /** @var \SplFileInfo $actionFile */
            foreach ($recursiveIterator as $actionFile) {
                $action = $this->string->upperCaseWords($moduleName, '_', '/') . '/Controller' . substr($actionFile->getPathname(), strlen($actionDir));
//                $actions[] = $this->modulesDirectory->getRelativePath($actionFile->getPathname());
                $actions[] = $action;
            }
        }
        return $actions;
    }

    /**
     * Get module directory by directory type
     *
     * @param string $type
     * @param string $moduleName
     * @return string
     */
    public function getModuleDir($type, $moduleName)
    {
        if (isset($this->customModuleDirs[$moduleName][$type])) {
            return $this->customModuleDirs[$moduleName][$type];
        }
        return $this->moduleDirs->getDir($moduleName, $type);
    }

    /**
     * Set path to the corresponding module directory
     *
     * @param string $moduleName
     * @param string $type directory type (etc, controllers, locale etc)
     * @param string $path
     * @return void
     */
    public function setModuleDir($moduleName, $type, $path)
    {
        $this->customModuleDirs[$moduleName][$type] = $path;
    }
}
