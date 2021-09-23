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
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
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
class ControllerFrontSendResponseBefore implements ObserverInterface
{
    /**
     * The Config
     *
     * @var Config
     */
    private $_config;

    /**
     * The Cookie Helper
     *
     * @var Cookie
     */
    private $_cookieHelper;

    /**
     * Registry
     *
     * @var Registry
     */
    private $_registry;

    /**
     * Message Manager
     *
     * @var ManagerInterface
     */
    private $_messageManager;

    /**
     * Constructor
     *
     * @param Config           $config         The Config
     * @param Cookie           $cookieHelper   The Cookie Helper
     * @param Registry         $registry       The Registry
     * @param ManagerInterface $messageManager The Message Manager
     */
    public function __construct(
        Config $config,
        Cookie $cookieHelper,
        Registry $registry,
        ManagerInterface $messageManager
    ) {
        $this->_config = $config;
        $this->_cookieHelper = $cookieHelper;
        $this->_registry = $registry;
        $this->_messageManager = $messageManager;
    }

    /**
     * Execute function
     *
     * @param Observer $observer The Observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->_config->isEnabled()) {
            $coupon = $this->_registry->registry('sourceknowledge_shopping_ads_discounturl_coupon');
            $message = $this->_registry->registry('sourceknowledge_shopping_ads_discounturl_message');

            if ($coupon) {
                $this->_cookieHelper->setCookie($coupon);
            }

            if ($message) {
                if ($message['error']) {
                    $this->_messageManager->addError($message['message']);
                } else {
                    $this->_messageManager->addSuccess($message['message']);
                }
            }
        }
    }
}
