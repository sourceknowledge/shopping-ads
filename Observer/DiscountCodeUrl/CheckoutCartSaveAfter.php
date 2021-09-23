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
use Sourceknowledge\ShoppingAds\Helper\Cart;
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
class CheckoutCartSaveAfter implements ObserverInterface
{
    /**
     * Config
     *
     * @var Config
     */
    private $_config;

    /**
     * Cookie Helper
     *
     * @var Cookie
     */
    private $_cookieHelper;

    /**
     * Cart
     *
     * @var Cart
     */
    private $_cartHelper;

    /**
     * Constructor
     *
     * @param Config $config       Config
     * @param Cookie $cookieHelper Cookie Helper
     * @param Cart   $cartHelper   Cart Helper
     */
    public function __construct(
        Config $config,
        Cookie $cookieHelper,
        Cart $cartHelper
    ) {
        $this->_config       = $config;
        $this->_cookieHelper = $cookieHelper;
        $this->_cartHelper   = $cartHelper;
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
            $coupon = $this->_cookieHelper->getCookie();
            if ($coupon) {
                $cart = $observer->getData('cart');
                if ($cart) {
                    $this->_cartHelper->applyCoupon($cart->getQuote(), $coupon);
                }
            }
        }
    }
}
