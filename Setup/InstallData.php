<?php

/**
 * SourceKnowledge Shopping Ads
 *
 * PHP version 7
 *
 * Copyright © Sourceknowledge. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 Sourceknowledge. All rights reserved.
 * @license   LICENSE.txt GNU GENERAL PUBLIC LICENSE
 * @link      https://www.sourceknowledge.com/
 */

namespace Sourceknowledge\ShoppingAds\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Framework\Setup\InstallDataInterface;

/**
 * Class InstallData
 *
 * Holds the installation configuration.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 Sourceknowledge. All rights reserved.
 * @license   LICENSE.txt GNU GENERAL PUBLIC LICENSE
 * @link      https://www.sourceknowledge.com/
 */
class InstallData implements InstallDataInterface
{
    /**
     * Integration manager.
     *
     * @var ConfigBasedIntegrationManager
     */
    private $_integrationManager;

    /**
     * InstallData constructor.
     *
     * @param ConfigBasedIntegrationManager $integrationManager Integration manager.
     */
    public function __construct(ConfigBasedIntegrationManager $integrationManager)
    {
        $this->_integrationManager = $integrationManager;
    }

    /**
     * Calls installation of the module.
     *
     * @param ModuleDataSetupInterface $setup   Setup interface.
     * @param ModuleContextInterface   $context Module context.
     *
     * @return void
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->_integrationManager
            ->processIntegrationConfig(['Sourceknowledge_ShoppingAds']);
    }
}
