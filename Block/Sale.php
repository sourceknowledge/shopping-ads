<?php

/**
 * SourceKnowledge Shopping Ads
 *
 * PHP version 7
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */

namespace Sourceknowledge\ShoppingAds\Block;

use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Customer;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Sourceknowledge\ShoppingAds\Helper\Data;
use Sourceknowledge\ShoppingAds\Model\Pixel;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Sale
 * Responsible to load the sale info for pixel.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */
class Sale extends AbstractPixel
{
    /**
     * Sale order.
     *
     * @var Order
     */
    protected $order;

    /**
     * Order factory.
     *
     * @var OrderFactory $orderFactory
     */
    protected $orderFactory;

    /**
     * Time zone.
     *
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * Current customer.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * AbstractPixel constructor.
     *
     * @param Context                  $context         Context.
     * @param Data                     $helper          Data helper.
     * @param ProductMetadataInterface $productMetadata Product metadata.
     * @param Registry                 $registry        Object registry.
     * @param Session                  $checkoutSession Checkout session.
     * @param PriceCurrency            $price           Price.
     * @param OrderFactory             $orderFactory    Order factory.
     * @param TimezoneInterface        $timezone        Time zone.
     * @param Customer                 $customer        Current customer.
     * @param array                    $data            Extra data.
     */
    public function __construct(
        Context $context,
        Data $helper,
        ProductMetadataInterface $productMetadata,
        Registry $registry,
        Session $checkoutSession,
        PriceCurrency $price,
        OrderFactory $orderFactory,
        TimezoneInterface $timezone,
        Customer $customer,
        array $data
    ) {
        parent::__construct(
            $context,
            $helper,
            $productMetadata,
            $registry,
            $checkoutSession,
            $price,
            $data
        );
        if ($this->checkoutSession->getLastRealOrder()) {
            $this->order = $this->checkoutSession->getLastRealOrder();
        }

        $this->orderFactory = $orderFactory;
        $this->timezone     = $timezone;
        $this->customer     = $customer;
    }

    /**
     * Gets query param for the pixel
     *
     * @return string
     */
    public function getQueryParams()
    {
        $data = $this->getOrderData();

        return $this->buildQueryParams(Pixel::EVENT_TYPE_SALE, $data);
    }

    /**
     * Gets Order Data
     *
     * @return array
     */
    protected function getOrderData()
    {
        $data = [];
        if (empty($this->order)) {
            return $data;
        }

        $data = $this->processOrderData($this->order, $data);
        $data = $this->processCustomerData($this->order, $data);
        $data = $this->processCouponCodes($this->order, $data);

        return $data;
    }

    /**
     * Processes Order Data
     *
     * @param Order $order Order information.
     * @param array $data  Order data.
     *
     * @return array
     */
    protected function processOrderData(Order $order, $data)
    {
        foreach ($order->getAllItems() as $item) {
            $productIds[] = $item->getId();
        }

        $orderId    = $order->getRealOrderId();
        $subTotal   = $order->getSubtotal();
        $createdAt  = $order->getCreatedAt();
        $customerId = $order->getCustomerId();

        $orderTime = !empty($createdAt)
            ? $this->timezone->date($createdAt)->getTimestamp()
            : '';
        $orderAmt  = !empty($subTotal) ? $this->formatPrice($subTotal) : '';

        $data [Pixel::VAR_PRODUCT_ID]   = !empty($productIds)
            ? implode(',', $productIds)
            : '';
        $data [Pixel::VAR_ORDER_ID]     = $orderId;
        $data [Pixel::VAR_ORDER_AMOUNT] = $orderAmt;

        $data [Pixel::VAR_TR_DATA] = [
            'confirmed_at'   => $orderTime,
            'customer_id'    => !empty($customerId) ? $customerId : '',
            'products_price' => $orderAmt,
        ];

        return $data;
    }

    /**
     * Processes Customer Data
     *
     * @param Order $order Order information.
     * @param array $data  Customer data.
     *
     * @return array
     */
    protected function processCustomerData(Order $order, $data)
    {
        if ($order->getCustomerId() === null) {
            $customerEmail = $order->getBillingAddress()->getEmail();
        } else {
            $customer      = $this->customer->load($order->getCustomerId());
            $customerEmail = $customer->getEmail();
            $orderCount    = !empty($customer)
                ? $this->getOrdersCount($customer)
                : 0;
            $orderCount    = $this->formatQty($orderCount);
        }

        // Set orders count.
        $data[Pixel::VAR_ORDERS_COUNT] = !empty($orderCount) ? $orderCount : '';
        // Set email hash.
        $email = strtolower(trim($customerEmail));
        if (!empty($email)) {
            $data[Pixel::VAR_EMAIL_HASH] = $this->helper->hash($customerEmail);
        }

        return $data;
    }

    /**
     * Process Coupon Codes
     *
     * @param Order $order Order information
     * @param array $data  Order data.
     *
     * @return array
     */
    protected function processCouponCodes(Order $order, array $data)
    {
        $coupon = $order->getCouponCode();
        if (!empty($coupon)) {
            $data[Pixel::VAR_COUPON_CODE] = $coupon;
        }

        return $data;
    }

    /**
     * Gets Orders count for customer
     *
     * @param Customer $customer Customer information.
     *
     * @return int|void
     */
    protected function getOrdersCount(Customer $customer)
    {
        $customerId = $customer->getId();
        if (empty($customerId)) {
            return 0;
        }

        $orders = $this->orderFactory->create()->getCollection()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'customer_id',
            $customerId
        )->setOrder(
            'created_at',
            'desc'
        );

        return !empty($orders) ? count($orders) : 0;
    }
}
