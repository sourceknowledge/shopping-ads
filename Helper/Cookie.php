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
     * Cookie Manager
     *
     * @var CookieManagerInterface
     */
    private $_cookieManager;

    /**
     * Cookie MetadataFactory
     *
     * @var CookieMetadataFactory
     */
    private $_cookieMetadataFactory;

    /**
     * Session Manager Interface
     *
     * @var SessionManagerInterface
     */
    private $_sessionManager;

    /**
     * The Config
     *
     * @var Config
     */
    private $_config;

    /**
     * Cookie constructor.
     *
     * @param CookieManagerInterface  $cookieManager         Cookie Manager
     * @param CookieMetadataFactory   $cookieMetadataFactory Cookie Meta Data
     * @param SessionManagerInterface $sessionManager        Session Manager
     * @param Config                  $config                Config
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager,
        Config $config
    ) {
        $this->_cookieManager         = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager        = $sessionManager;
        $this->_config                = $config;
    }

    /**
     * Gets the session's currently applied coupon code or an empty .
     *
     * @return string|null
     */
    public function getCookie()
    {
        $value = $this->_cookieManager->getCookie($this->_config->getCookieName());

        return $value ? $value : null;
    }

    /**
     * Sets the coupon code cookie, so we can remember it in the current session.
     *
     * @param mixed $value The Value
     *
     * @return null
     */
    public function setCookie($value)
    {
        $cookieLifetime = $this->_config->getCookieLifetime();
        $metadata       = $this->_cookieMetadataFactory->createPublicCookieMetadata();

        $metadata->setPath($this->_sessionManager->getCookiePath());
        $metadata->setDomain($this->_sessionManager->getCookieDomain());
        $metadata->setHttpOnly(false);

        if ($cookieLifetime > 0) {
            $metadata->setDuration($cookieLifetime);
        }

        $this->_cookieManager->setPublicCookie($this->_config->getCookieName(), $value, $metadata);
    }

    /**
     * Deletes the coupon code cookie.
     *
     * @return null
     */
    public function deleteCookie()
    {
        $metadata = $this->_cookieMetadataFactory->createPublicCookieMetadata();

        $metadata->setPath($this->_sessionManager->getCookiePath());
        $metadata->setDomain($this->_sessionManager->getCookieDomain());
        $metadata->setHttpOnly(false);

        $this->_cookieManager->deleteCookie($this->_config->getCookieName(), $metadata);
    }
}
