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

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

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
class Cookie
{
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * Cookie constructor.
     *
     * @param CookieManagerInterface  $cookieManager
     * @param CookieMetadataFactory   $cookieMetadataFactory
     * @param SessionManagerInterface $sessionManager
     * @param Config                  $config
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager,
        Config $config
    )
    {
        $this->cookieManager         = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager        = $sessionManager;
        $this->config                = $config;
    }

    /**
     * Gets the session's currently applied coupon code or an empty .
     *
     * @return string|null
     */
    public function getCookie()
    {
        $value = $this->cookieManager->getCookie($this->config->getCookieName());

        return $value ? $value : null;
    }

    /**
     * Sets the coupon code cookie so we can remember it in the current session.
     *
     * @param $value
     * @return null
     */
    public function setCookie($value)
    {
        $cookieLifetime = $this->config->getCookieLifetime();
        $metadata       = $this->cookieMetadataFactory->createPublicCookieMetadata();

        $metadata->setPath($this->sessionManager->getCookiePath());
        $metadata->setDomain($this->sessionManager->getCookieDomain());
        $metadata->setHttpOnly(false);

        if ($cookieLifetime > 0) {
            $metadata->setDuration($cookieLifetime);
        }

        $this->cookieManager->setPublicCookie($this->config->getCookieName(), $value, $metadata);
    }

    /**
     * Deletes the coupon code cookie.
     *
     * @return null
     */
    public function deleteCookie()
    {
        $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();

        $metadata->setPath($this->sessionManager->getCookiePath());
        $metadata->setDomain($this->sessionManager->getCookieDomain());
        $metadata->setHttpOnly(false);

        $this->cookieManager->deleteCookie($this->config->getCookieName(), $metadata);
    }
}
