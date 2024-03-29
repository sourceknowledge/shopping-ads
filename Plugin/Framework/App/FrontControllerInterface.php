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
namespace Sourceknowledge\ShoppingAds\Plugin\Framework\App;

use Magento\Framework\App\RequestInterface;
use Magento\SalesRule\Model\Coupon;
use Sourceknowledge\ShoppingAds\Helper\Cart;
use Sourceknowledge\ShoppingAds\Helper\Config;
use Sourceknowledge\ShoppingAds\Helper\Cookie;
use Magento\SalesRule\Model\Rule;
use Magento\Framework\Registry;
use Magento\Checkout\Model\Session;

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
class FrontControllerInterface
{
    /**
     * The Request Interface
     *
     * @var RequestInterface
     */
    private $_request;

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
     * The Cart Helper
     *
     * @var Cart
     */
    private $_cartHelper;

    /**
     * Coupon Model
     *
     * @var Coupon
     */
    private $_couponModel;

    /**
     * The Rule Model
     *
     * @var Rule
     */
    private $_ruleModel;

    /**
     * The Registry
     *
     * @var Registry
     */
    private $_registry;

    /**
     * The Session
     *
     * @var Session
     */
    private $_checkoutSession;

    /**
     * Constructor
     *
     * @param RequestInterface $request         Request
     * @param Config           $config          Config
     * @param Cookie           $cookieHelper    Cookie Helper
     * @param Cart             $cartHelper      Cart Helper
     * @param Coupon           $couponModel     Coupon Model
     * @param Rule             $ruleModel       Rule Model
     * @param Registry         $registry        Registry
     * @param Session          $checkoutSession Checkout Session
     */
    public function __construct(
        RequestInterface $request,
        Config $config,
        Cookie $cookieHelper,
        Cart $cartHelper,
        Coupon $couponModel,
        Rule $ruleModel,
        Registry $registry,
        Session $checkoutSession
    ) {
        $this->_request = $request;
        $this->_config = $config;
        $this->_cookieHelper = $cookieHelper;
        $this->_cartHelper = $cartHelper;
        $this->_couponModel = $couponModel;
        $this->_ruleModel = $ruleModel;
        $this->_registry = $registry;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * If coupon code is provided by the URL, remember it for the duration of
     * the session.
     *
     * @param \Magento\Framework\App\FrontControllerInterface $subject (not used)
     *
     * @return void
     */
    public function beforeDispatch(\Magento\Framework\App\FrontControllerInterface $subject)
    {
        if ($this->_config->isEnabled()) {
            $queryParameter = $this->_config->getUrlParameter();

            // Discount code passed through the URL via query string
            $coupon = $this->_request->getParam($queryParameter);
            if ($coupon) {
                $invalidMessage = "Discount code <strong>$coupon</strong> is invalid";
                $expiredMessage = "Unfortunately, the <strong>$coupon</strong> discount code is expired";
                $consumedMessage = "Unfortunately, the <strong>$coupon</strong> discount code has been fully consumed";

                $this->_couponModel->loadByCode($coupon);

                if ($this->_couponModel->getId()) {
                    $this->_ruleModel->load($this->_couponModel->getRuleId());

                    if ($this->_ruleModel->getId()) {
                        $today = strtotime(date("Y-m-d"));
                        $startDay = $this->_ruleModel->getFromDate();
                        $expirationDay = $this->_ruleModel->getToDate();

                        $numUses = $this->_couponModel->getTimesUsed();
                        $maxUses = $this->_couponModel->getUsageLimit();

                        $usesPerCustomer = $this->_couponModel->getUsagePerCustomer();

                        // Discount code is expired
                        if ($expirationDay && strtotime($expirationDay) < $today) {
                            $this->_registry->register(
                                'sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($expiredMessage),
                                'error' => true
                                ]
                            );
                        } elseif ($startDay && strtotime($startDay) > $today) {
                            $this->_registry->register(
                                'sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($invalidMessage),
                                'error' => true
                                ]
                            );
                        } elseif ($maxUses && $numUses >= $maxUses) {
                            $this->_registry->register(
                                'sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($consumedMessage),
                                'error' => true]
                            );
                        } else {
                            $successMessage = "Discount code <strong>$coupon</strong> will be applied to your order during checkout";
                            if ($usesPerCustomer && $usesPerCustomer > 0) {
                                if ($usesPerCustomer > 1) {
                                    $successMessage .= " unless you've already fully consumed it (code is only valid for up to $usesPerCustomer orders";
                                } else {
                                    $successMessage .= " unless you've already used it (code is only valid for one order";
                                }

                                $successMessage .= " per customer)";
                            }

                            $this->_registry->register('sourceknowledge_shopping_ads_discounturl_coupon', $coupon);
                            $this->_registry->register(
                                'sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($successMessage),
                                'error' => false
                                ]
                            );
                        }
                    } else {
                        $this->_registry->register(
                            'sourceknowledge_shopping_ads_discounturl_message', [
                            'message' => __($invalidMessage),
                            'error' => true
                            ]
                        );
                    }
                } else {
                    $this->_registry->register(
                        'sourceknowledge_shopping_ads_discounturl_message', [
                        'message' => __($invalidMessage),
                        'error' => true
                        ]
                    );
                }
            }
        }
    }

    /**
     * If a quote already exists, we need to apply the discount code to it
     * automatically (if possible) and before the response is rendered. This
     * covers us in the case that a user applies a discount code to the URL
     * after having a cart that's already full (which means the save cart
     * observer won't execute and therefore won't update the quote's price.) I
     * can't do this in beforeDispatch, because based on my own testing, it
     * seems that the session classes don't get populated until after
     * FrontController::dispatch() finishes.
     *
     * @param \Magento\Framework\App\FrontControllerInterface $subject (not used)
     * @param ResponseInterface|ResultInterface               $result  Return value of FrontController::dispatch()
     *
     * @return ResponseInterface|ResultInterface
     */
    public function afterDispatch(\Magento\Framework\App\FrontControllerInterface $subject, $result)
    {
        if ($this->_config->isEnabled()) {

            // If a quote already exists, apply the
            // discount automatically (if possible)
            $coupon = $this->_registry->registry('sourceknowledge_shopping_ads_discounturl_coupon');

            if ($coupon && $this->_checkoutSession->hasQuote()) {
                $this->_cartHelper->applyCoupon(
                    $this->_checkoutSession->getQuote(),
                    $coupon
                );
            }
        }

        return $result;
    }
}
