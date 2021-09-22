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
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * Cart Constructor
     *
     * @param Context                 $context
     * @param CartRepositoryInterface $quoteRepository
     * @param ManagerInterface        $messageManager
     */
    public function __construct(
        Context $context,
        CartRepositoryInterface $quoteRepository,
        ManagerInterface $messageManager
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->messageManager  = $messageManager;

        parent::__construct($context);
    }

    /**
     * Apply Coupon.
     *
     * @param Quote  $quote
     * @param string $coupon
     */
    public function applyCoupon(Quote $quote, string $coupon)
    {
        try {
            $quote->setCouponCode($coupon);
            $this->quoteRepository->save($quote->collectTotals());
        } catch (LocalizedException $e) {
            $this->messageManager->addError(
                __("Discount code <strong>$coupon</strong> couldn't be applied: " .
                    $e->getMessage())
            );
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __("Discount code <strong>$coupon</strong> couldn't be applied or is invalid")
            );
        }

        if ($quote->getCouponCode() != $coupon) {
            $this->messageManager->addError(
                __("Discount code <strong>$coupon</strong> is invalid. Verify that it's correct and try again.")
            );
        }
    }
}
