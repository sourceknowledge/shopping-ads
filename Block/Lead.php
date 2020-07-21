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

use Sourceknowledge\ShoppingAds\Model\Pixel;

/**
 * Class Lead
 * Responsible to load the pixel main code. Lead Type Pixel (All pages pixel).
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 SourceKnowledge (https://www.sourceknowledge.com/)
 * @license   https://www.sourceknowledge.com/LICENSE.txt MIT
 * @link      https://www.sourceknowledge.com/
 */
class Lead extends AbstractPixel
{
    /**
     * Gets query param for the pixel
     *
     * @return string
     */
    public function getQueryParams()
    {
        $data           = [];
        $currentProduct = $this->getCurrentProduct();
        if (!empty($currentProduct)) {
            $data = [Pixel::VAR_PRODUCT_ID => $currentProduct->getId()];
        }

        return $this->buildQueryParams(Pixel::EVENT_TYPE_VIEW, $data);
    }
}
