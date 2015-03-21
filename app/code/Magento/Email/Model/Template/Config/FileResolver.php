<?php
/**
 * Hierarchy config file resolver
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Email\Model\Template\Config;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Config\FileIteratorFactory;

class FileResolver extends \Magento\Framework\App\Config\FileResolver
{
    /**
     * {@inheritdoc}
     */
    public function get($filename, $scope)
    {
        return parent::get($filename, 'global');
    }
}
