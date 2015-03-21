<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Module;


class BasicModule implements ModuleInterface
{

    /**
     * Retrieve full path to a directory of certain type within a module
     *
     * @param string $type Type of module's directory to retrieve
     * @return string Path to the directory of this module
     * @throws \InvalidArgumentException if $type isn't recognized.
     */
    public function getDir($type)
    {
        $class = new \ReflectionClass(get_class($this));
        $rootDir = dirname($class->getFileName());
        $path = "$rootDir/$type";
        return $path;
    }
}
