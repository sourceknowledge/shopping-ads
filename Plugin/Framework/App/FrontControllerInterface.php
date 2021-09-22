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
     * @var RequestInterface
     */
    private $request;

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
     * @var Coupon
     */
    private $couponModel;

    /**
     * @var Rule
     */
    private $ruleModel;

    /**
     * @var Registry $registry
     */
    private $registry;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param Config $config
     * @param Cookie $cookieHelper
     * @param Cart $cartHelper
     * @param Coupon $couponModel
     * @param Rule $ruleModel
     * @param Registry $registry
     * @param Session $checkoutSession
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
        $this->request = $request;
        $this->config = $config;
        $this->cookieHelper = $cookieHelper;
        $this->cartHelper = $cartHelper;
        $this->couponModel = $couponModel;
        $this->ruleModel = $ruleModel;
        $this->registry = $registry;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * If coupon code is provided by the URL, remember it for the duration of
     * the session.
     *
     * @param \Magento\Framework\App\FrontControllerInterface $subject (not used)
     *
     * @return null
     */
    public function beforeDispatch(\Magento\Framework\App\FrontControllerInterface $subject)
    {
        if ($this->config->isEnabled()) {
            $queryParameter = $this->config->getUrlParameter();

            // Discount code passed through the URL via query string
            $coupon = $this->request->getParam($queryParameter);
            if ($coupon) {
                $invalidMessage = "Discount code <strong>$coupon</strong> is invalid";
                $expiredMessage = "Unfortunately, the <strong>$coupon</strong> discount code is expired";
                $consumedMessage = "Unfortunately, the <strong>$coupon</strong> discount code has been fully consumed";

                $this->couponModel->loadByCode($coupon);

                if ($this->couponModel->getId()) {
                    $this->ruleModel->load($this->couponModel->getRuleId());

                    if ($this->ruleModel->getId()) {
                        $today = strtotime(date("Y-m-d"));
                        $startDay = $this->ruleModel->getFromDate();
                        $expirationDay = $this->ruleModel->getToDate();

                        $numUses = $this->couponModel->getTimesUsed();
                        $maxUses = $this->couponModel->getUsageLimit();

                        $usesPerCustomer = $this->couponModel->getUsagePerCustomer();

                        // Discount code is expired
                        if ($expirationDay && strtotime($expirationDay) < $today) {
                            $this->registry->register('sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($expiredMessage),
                                'error' => true
                            ]);
                        }

                        // Discount hasn't started yet
                        elseif ($startDay && strtotime($startDay) > $today) {
                            $this->registry->register('sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($invalidMessage),
                                'error' => true
                            ]);
                        }

                        // Coupon has already been fully consumed
                        elseif ($maxUses && $numUses >= $maxUses) {
                            $this->registry->register('sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($consumedMessage),
                                'error' => true
                            ]);
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

                            $this->registry->register('sourceknowledge_shopping_ads_discounturl_coupon', $coupon);
                            $this->registry->register('sourceknowledge_shopping_ads_discounturl_message', [
                                'message' => __($successMessage),
                                'error' => false
                            ]);
                        }
                    } else {
                        $this->registry->register('sourceknowledge_shopping_ads_discounturl_message', [
                            'message' => __($invalidMessage),
                            'error' => true
                        ]);
                    }
                } else {
                    $this->registry->register('sourceknowledge_shopping_ads_discounturl_message', [
                        'message' => __($invalidMessage),
                        'error' => true
                    ]);
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
     * @param ResponseInterface|ResultInterface Return value of FrontController::dispatch()
     *
     * @return ResponseInterface|ResultInterface
     */
    public function afterDispatch(\Magento\Framework\App\FrontControllerInterface $subject, $result)
    {
        if ($this->config->isEnabled()) {

            // If a quote already exists, apply the
            // discount automatically (if possible)
            $coupon = $this->registry->registry('sourceknowledge_shopping_ads_discounturl_coupon');

            if ($coupon && $this->checkoutSession->hasQuote()) {
                $this->cartHelper->applyCoupon(
                    $this->checkoutSession->getQuote(),
                    $coupon
                );
            }
        }

        return $result;
    }
}
