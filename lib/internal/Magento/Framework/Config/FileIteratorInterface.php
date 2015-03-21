<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Config;


/**
 * Class FileIterator
 */
interface FileIteratorInterface extends \Iterator, \Countable
{
    /**
     *Rewind
     *
     * @return void
     */
    public function rewind();

    /**
     * Current
     *
     * @return string
     */
    public function current();

    /**
     * Key
     *
     * @return mixed
     */
    public function key();

    /**
     * Next
     *
     * @return void
     */
    public function next();

    /**
     * Valid
     *
     * @return bool
     */
    public function valid();

    /**
     * Convert to an array
     *
     * @return array
     */
    public function toArray();

    /**
     * Count
     *
     * @return int
     */
    public function count();
}
