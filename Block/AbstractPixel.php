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

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Checkout\Model\Session;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Quote\Model\Quote;
use Sourceknowledge\ShoppingAds\Model\Pixel;
use Sourceknowledge\ShoppingAds\Helper\Data;

/**
 * Class AbstractPixel
 * Abstraction for all SourceKnowledge pixel tracking templates.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */
abstract class AbstractPixel extends Template
{
    /**
     * Data helper.
     *
     * @var Data
     */
    protected $helper;

    /**
     * Product metadata.
     *
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * Price currency.
     *
     * @var PriceCurrency
     */
    protected $price;

    /**
     * Object registry.
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Quote item.
     *
     * @var Quote|null
     */
    protected $quote = null;

    /**
     * Checkout session.
     *
     * @var Session
     */
    protected $checkoutSession;

    /**
     * Shop value.
     *
     * @var string
     */
    protected $shop;

    /**
     * AbstractPixel constructor.
     *
     * @param Context                  $context         Context.
     * @param Data                     $helper          Data helper.
     * @param ProductMetadataInterface $productMetadata Product metadata.
     * @param Registry                 $registry        Object registry.
     * @param Session                  $checkoutSession Checkout session.
     * @param PriceCurrency            $price           Price.
     * @param array                    $data            Extra data.
     */
    public function __construct(
        Context $context,
        Data $helper,
        ProductMetadataInterface $productMetadata,
        Registry $registry,
        Session $checkoutSession,
        PriceCurrency $price,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->helper          = $helper;
        $this->productMetadata = $productMetadata;
        $this->registry        = $registry;
        $this->price           = $price;
        $this->checkoutSession = $checkoutSession;
        $this->shop            = $this->helper->filterDomain($this->getBaseUrl());
    }

    /**
     * Gets query param for the pixel
     *
     * @return string
     */
    abstract public function getQueryParams();

    /**
     * Overrides the behavior of the html output.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->helper->isModuleEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Gets the current Quote
     *
     * @return Quote
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function getCurrentQuote()
    {
        if (!$this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }

        return $this->quote;
    }

    /**
     * Round price
     *
     * @param float $amount Amount to format.
     *
     * @return float
     */
    protected function formatPrice($amount)
    {
        return $this->price->round($amount);
    }

    /**
     * Formats Qty.
     *
     * @param float $value Quantity to format.
     *
     * @return int
     */
    protected function formatQty($value)
    {
        return (int) number_format($value, 2, '.', '');
    }

    /**
     * Builds query params
     *
     * @param string $eventName Event name.
     * @param array  $arguments Event arguments.
     *
     * @return string
     */
    protected function buildQueryParams($eventName, $arguments = [])
    {
        $arguments = array_merge(
            [
                Pixel::SHOP_KEY       => $this->shop,
                Pixel::EVENT_TYPE_KEY => $eventName,
                Pixel::VAR_VERSION    => $this->helper->getMageVersionInfo(),
            ],
            $arguments
        );

        return http_build_query($arguments);
    }

    /**
     * Gets current product
     *
     * @return ProductInterface|null
     */
    protected function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
}
