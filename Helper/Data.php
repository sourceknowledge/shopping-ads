<?php

/**
 * SourceKnowledge Shopping Ads
 *
 * PHP version 7
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */

namespace Sourceknowledge\ShoppingAds\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Module\ModuleListInterface;

/**
 * Class Data
 *
 * Helper class for SourceKnowledge common un-related functions functions.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */
class Data extends AbstractHelper
{
    /**
     * Module Code/Name
     */
    const MODULE_NAME = 'Sourceknowledge_ShoppingAds';

    /**
     * List of modules.
     *
     * @var ModuleListInterface
     */
    protected $moduleList;

    /**
     * Product metadata.
     *
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * Data Constructor
     *
     * @param Context                  $context         Current context.
     * @param ModuleListInterface      $moduleList      List of modules.
     * @param ProductMetadataInterface $productMetadata Product metadata.
     */
    public function __construct(
        Context $context,
        ModuleListInterface $moduleList,
        ProductMetadataInterface $productMetadata
    ) {
        $this->moduleList      = $moduleList;
        $this->productMetadata = $productMetadata;

        parent::__construct($context);
    }

    /**
     * Is Module Enabled
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->_moduleManager->isEnabled(self::MODULE_NAME);
    }

    /**
     * Filters domain by removing https://www
     * Extracts host
     *
     * @param string $url Absolute url.
     *
     * @return string|null
     */
    public function filterDomain($url)
    {
        $host     = parse_url($url, PHP_URL_HOST);
        $toFilter = $host ? $host : $url;

        return preg_replace('#^(?:https?://)?(?:www[.])?#i', '$1', $toFilter);
    }

    /**
     * Hash a specified value.
     *
     * @param string $val Value to hash.
     *
     * @return string
     */
    public function hash($val)
    {
        return hash('sha256', $val);
    }

    /**
     * Generates an error info for sending to SK.
     *
     * @param string          $class Class which originated the error.
     * @param \Exception|null $e     Error if it was generated.
     *
     * @return string
     */
    public function getErrorInfo($class, \Exception $e = null)
    {
        $line    = '';
        $version = $this->getModuleVersion();
        if (!empty($e)) {
            $line = $e->getLine();
        }

        return "{$version}_{$class}_{$line}";
    }

    /**
     * Get Extension Version
     *
     * @return string
     */
    public function getModuleVersion()
    {
        return $this->moduleList->getOne(self::MODULE_NAME)['setup_version'];
    }

    /**
     * Gets Version and edition of Magento
     *
     * @return string
     */
    public function getMageVersionInfo()
    {
        $version = $this->productMetadata->getVersion();
        $edition = $this->productMetadata->getEdition();

        return $edition . '-' . $version;
    }
}
