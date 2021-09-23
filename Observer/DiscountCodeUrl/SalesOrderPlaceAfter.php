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
namespace Sourceknowledge\ShoppingAds\Observer\DiscountCodeUrl;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Sourceknowledge\ShoppingAds\Helper\Config;
use Sourceknowledge\ShoppingAds\Helper\Cookie;

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
class SalesOrderPlaceAfter implements ObserverInterface
{
    /**
     * The Config
     *
     * @var Config
     */
    private $_config;

    /**
     * The Cookie
     *
     * @var Cookie
     */
    private $_cookieHelper;

    /**
     * Constructor
     *
     * @param Config $config       The Config
     * @param Cookie $cookieHelper The Cookie Helper
     */
    public function __construct(
        Config $config,
        Cookie $cookieHelper
    ) {
        $this->_config       = $config;
        $this->_cookieHelper = $cookieHelper;
    }

    /**
     * Execute function
     *
     * @param Observer $observer The Observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        // Once we've placed an order, we should delete the coupon cookie so
        if ($this->_config->isEnabled()) {
            $this->_cookieHelper->deleteCookie();
        }
    }
}
