<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */ 
namespace Magento\Framework\Module;

interface ModuleInterface
{
    /**
     * Retrieve full path to a directory of certain type within a module
     *
     * @param string $type Type of module's directory to retrieve
     * @return string
     * @throws \InvalidArgumentException if $type isn't recognized.
     */
    public function getDir($type);
}
