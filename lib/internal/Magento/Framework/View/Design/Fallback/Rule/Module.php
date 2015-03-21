<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\View\Design\Fallback\Rule;

use Magento\Framework\Module\Dir;

/**
 * Class with simple substitution parameters to values
 */
class Module implements RuleInterface
{
    /**
     * @var string pattern for sub path under the modules view directory
     */
    private $pattern;

    /**
     * Optional params for rule
     *
     * @var array
     */
    private $optionalParams;

    /**
     * @var Dir
     */
    private $moduleDir;

    /**
     * Constructor
     *
     * @param Dir $moduleDir
     * @param string $pattern
     * @param array $optionalParams
     */
    public function __construct(Dir $moduleDir, $pattern, array $optionalParams = [])
    {
        $this->moduleDir = $moduleDir;
        $this->pattern = $pattern;
        $this->optionalParams = $optionalParams;
    }

    /**
     * Get ordered list of folders to search for a file
     *
     * @param array $params array of parameters such as ['area' => $area, 'theme' => $theme, 'locale' => $locale]
     * @return array folders to perform a search
     * @throws \InvalidArgumentException
     */
    public function getPatternDirs(array $params)
    {
        $moduleName = $params['namespace'] . '_' . $params['module'];
        $pattern = $this->pattern;
        if (preg_match_all('/<([a-zA-Z\_]+)>/', $pattern, $matches)) {
            foreach ($matches[1] as $placeholder) {
                if (empty($params[$placeholder])) {
                    if (in_array($placeholder, $this->optionalParams)) {
                        return [];
                    } else {
                        throw new \InvalidArgumentException("Required parameter '{$placeholder}' was not passed");
                    }
                }
                $pattern = str_replace('<' . $placeholder . '>', $params[$placeholder], $pattern);
            }
        }
        $viewDir = $this->moduleDir->getDir($moduleName, 'view');
        return [$viewDir . '/' . $pattern];
    }
}
