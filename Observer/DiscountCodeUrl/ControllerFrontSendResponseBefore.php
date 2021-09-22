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
     * @var Config
     */
    private $config;

    /**
     * @var Cookie
     */
    private $cookieHelper;

    /**
     * @var Registry $registry
     */
    private $registry;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * Constructor
     *
     * @param Config           $config
     * @param Cookie           $cookieHelper
     * @param Registry         $registry
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Config $config,
        Cookie $cookieHelper,
        Registry $registry,
        ManagerInterface $messageManager
    ) {
        $this->config = $config;
        $this->cookieHelper = $cookieHelper;
        $this->registry = $registry;
        $this->messageManager = $messageManager;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isEnabled()) {
            $coupon = $this->registry->registry('sourceknowledge_shopping_ads_discounturl_coupon');
            $message = $this->registry->registry('sourceknowledge_shopping_ads_discounturl_message');

            if ($coupon) {
                $this->cookieHelper->setCookie($coupon);
            }

            if ($message) {
                if ($message['error']) {
                    $this->messageManager->addError($message['message']);
                } else {
                    $this->messageManager->addSuccess($message['message']);
                }
            }
        }
    }
}
