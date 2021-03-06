/**
 * SourceKnowledge Shopping Ads
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
define(
    [
    'jquery'
    ], function ($) {
        'use strict';

        /**
         * Injects the SourceKnowledge pixel code with the specified config.
         *
         * @param {Object} config
         */
        return function (config) {
            if (config.query) {
                (function (d,t,u,p,e,f) {
                    e=d.createElement(t);f=d.getElementsByTagName(t)[0];
                    e.async=1;e.src=u+'?'+p+'&cb='+Math.floor(Math.random()*999999);f.parentNode.insertBefore(e,f);
                })(document,'script', '//upx.provenpixel.com/mage.js.php', config.query);
            }
        }
    }
);
