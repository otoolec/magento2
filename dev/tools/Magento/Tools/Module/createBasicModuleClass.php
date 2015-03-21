<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

$rootDir = realpath(__DIR__ . '/../../../../../');

$appCode = $rootDir . '/app/code/';

$vendorIterator = new \DirectoryIterator($appCode);
foreach ($vendorIterator as $vendorDir) {
    if (!$vendorDir->isDir() || $vendorDir->isDot()) {
        continue;
    }
    $vendorName = $vendorDir->getBasename();
    $moduleIterator = new \DirectoryIterator($vendorDir->getPathname());
    foreach ($moduleIterator as $moduleDir) {
        if (!$moduleDir->isDir() || $moduleDir->isDot()) {
            continue;
        }
        $moduleName = $moduleDir->getBasename();
        $moduleFile = $moduleDir->getPathname() . '/Module.php';
        if (!file_exists($moduleFile)) {
            $namespace = "$vendorName\\$moduleName";
            write_basic_module($moduleFile, $namespace);
        }
    }
}


function write_basic_module($moduleFile, $namespace)
{
    $moduleClassContents = <<<CONTENTS
<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace $namespace;

use Magento\Framework\Module\BasicModule;

class Module extends BasicModule
{

}

CONTENTS;

    file_put_contents($moduleFile, $moduleClassContents);
}
