<?php

namespace Sourceknowledge\ShoppingAds\Setup\Patch\Data;

use Magento\Authorization\Model\Acl\AclRetriever;
use Magento\Authorization\Model\ResourceModel\Rules\Collection;
use Magento\Authorization\Model\ResourceModel\Rules\CollectionFactory;
use Magento\Authorization\Model\Rules;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\IntegrationException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Integration\Model\Integration;
use Magento\Integration\Model\IntegrationFactory;

/**
 * DataPatch for integration setup
 *
 */
class DataPatch implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * Integration manager.
     *
     * @var ConfigBasedIntegrationManager
     */
    private ConfigBasedIntegrationManager $integrationManager;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ConfigBasedIntegrationManager $integrationManager
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup, ConfigBasedIntegrationManager $integrationManager)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->integrationManager = $integrationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();
        // Your setup script
        $this->integrationManager
            ->processIntegrationConfig(['Sourceknowledge_ShoppingAds']);
        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
