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

namespace Sourceknowledge\ShoppingAds\Model;

/**
 * Class Pixel
 *
 * Model for SourceKnowledge Shopping Ads pixel
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */
class Pixel
{
    /**
     * Mandatory fields in the request
     */
    const SHOP_KEY       = 'shop';
    const EVENT_TYPE_KEY = 'event';

    /**
     * Event Types
     */
    const EVENT_TYPE_VIEW = 'view';
    const EVENT_TYPE_CART = 'cart';
    const EVENT_TYPE_SALE = 'sale';

    /**
     * Main tracking variables
     */
    const VAR_PRODUCT_ID   = 'product_id';
    const VAR_ORDER_ID     = 'order_id';
    const VAR_ORDER_AMOUNT = 'order_amount';
    const VAR_COUPON_CODE  = 'coupon_code';
    const VAR_ORDERS_COUNT = 'orders_count';
    const VAR_EMAIL_HASH   = 'ehash';
    const VAR_TR_DATA      = 'trdata';
    const VAR_ERROR        = 'err';
    const VAR_VERSION      = 'ver';

}
