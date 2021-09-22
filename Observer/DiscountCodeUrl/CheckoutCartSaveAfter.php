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
     * @var Config
     */
    private $config;

    /**
     * @var Cookie
     */
    private $cookieHelper;

    /**
     * @var Cart
     */
    private $cartHelper;

    /**
     * Constructor
     *
     * @param Config          $config
     * @param Cookie          $cookieHelper
     * @param Cart            $cartHelper
     */
    public function __construct(
        Config $config,
        Cookie $cookieHelper,
        Cart $cartHelper
    ) {
        $this->config       = $config;
        $this->cookieHelper = $cookieHelper;
        $this->cartHelper   = $cartHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isEnabled()) {
            $coupon = $this->cookieHelper->getCookie();
            if ($coupon) {
                $cart = $observer->getData('cart');
                if ($cart) {
                    $this->cartHelper->applyCoupon($cart->getQuote(), $coupon);
                }
            }
        }
    }
}
