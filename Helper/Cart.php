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
namespace Sourceknowledge\ShoppingAds\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;

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
class Cart extends AbstractHelper
{
    /**
     * Cart Repo
     *
     * @var CartRepositoryInterface
     */
    private $_quoteRepository;

    /**
     * Manager Interface
     *
     * @var ManagerInterface
     */
    private $_messageManager;

    /**
     * Cart Constructor
     *
     * @param Context                 $context         Context
     * @param CartRepositoryInterface $quoteRepository Quote Repo
     * @param ManagerInterface        $messageManager  Message Manager
     */
    public function __construct(
        Context $context,
        CartRepositoryInterface $quoteRepository,
        ManagerInterface $messageManager
    ) {
        $this->_quoteRepository = $quoteRepository;
        $this->_messageManager  = $messageManager;

        parent::__construct($context);
    }

    /**
     * Apply Coupon.
     *
     * @param Quote  $quote  Quote
     * @param string $coupon Coupon
     *
     * @return void
     */
    public function applyCoupon(Quote $quote, string $coupon)
    {
        try {
            $quote->setCouponCode($coupon);
            $this->_quoteRepository->save($quote->collectTotals());
        } catch (LocalizedException $e) {
            $this->_messageManager->addError(__("Discount code <strong>$coupon</strong> couldn't be applied: " . $e->getMessage()));
        } catch (\Exception $e) {
            $this->_messageManager->addError(
                __("Discount code <strong>$coupon</strong> couldn't be applied or is invalid")
            );
        }

        if ($quote->getCouponCode() != $coupon) {
            $this->_messageManager->addError(
                __("Discount code <strong>$coupon</strong> is invalid. Verify that it's correct and try again.")
            );
        }
    }
}
