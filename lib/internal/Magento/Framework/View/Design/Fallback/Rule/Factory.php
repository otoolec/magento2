<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\View\Design\Fallback\Rule;

use \Magento\Framework\ObjectManagerInterface;

class Factory
{
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param $ruleType
     * @param array $data
     * @return RuleInterface
     * @throws \Exception
     */
    public function create($ruleType, array $data)
    {
        switch ($ruleType) {
            case 'Module':
                return $this->objectManager->create('Magento\Framework\View\Design\Fallback\Rule\Module', $data);
            default:
                return $this->handleDefault($ruleType, $data);
        }
    }

    /**
     * @param $ruleType
     * @param $data
     * @return RuleInterface
     * @throws \Exception
     */
    protected function handleDefault($ruleType, $data)
    {
        throw new \Exception("Not able to construct Fallback Rule for $ruleType");
    }
}
