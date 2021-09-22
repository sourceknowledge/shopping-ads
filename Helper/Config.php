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
class Config extends AbstractHelper
{
    /**
     * The URL parameter we should look for to set the current coupon code.
     *
     * @var string URL parameter containing the coupon code
     */
    const DEFAULT_URL_PARAMETER = 'sk_coupon';

    /**
     * When a code is supplied via the URL, a cookie is set that allows us to
     * remember it during a session.
     *
     * @var string Name of cookie that stores discount code
     */
    const COUPON_COOKIE_NAME = 'sk_discount_coupon_url_code';

    /**
     * This is how long a browser session should remember the last coupon code
     * that was supplied via the URL in seconds. A default value of 0 means
     * the cookie will last as long as the session (i.e. until the browser tab
     * or window is closed.)
     *
     * @var string Default cookie lifetime in seconds
     */
    const DEFAULT_COOKIE_LIFETIME = 0;

    /**
     * @var string Whether or not the module is enabled
     */
    const ENABLED_CONFIG_PATH = 'promo/discounturl/enabled';

    /**
     * @var string How long the cookie should last
     */
    const COOKIE_LIFETIME_CONFIG_PATH = 'promo/discounturl/cookie_lifetime';

    /**
     * Returns whether or not the module is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {

        return $this->scopeConfig->isSetFlag(self::ENABLED_CONFIG_PATH);
    }

    /**
     * Gets url parameter
     *
     * @return string
     */
    public function getUrlParameter(): string
    {

        return self::DEFAULT_URL_PARAMETER;
    }

    /**
     * Gets Cookie Lifetime.
     *
     * @return int
     */
    public function getCookieLifetime(): int
    {

        $value = $this->scopeConfig->getValue(self::COOKIE_LIFETIME_CONFIG_PATH);

        return (int) (is_null($value) || '' === $value ? self::DEFAULT_COOKIE_LIFETIME : $value);
    }

    /**
     * Gets Cookie Name
     *
     * @return string
     */
    public function getCookieName(): string
    {

        return self::COUPON_COOKIE_NAME;
    }
}
