<?php
/**
 * ViraXpress - https://www.viraxpress.com
 *
 * LICENSE AGREEMENT
 *
 * This file is part of the ViraXpress package and is licensed under the ViraXpress license agreement.
 * You can view the full license at:
 * https://www.viraxpress.com/license
 *
 * By utilizing this file, you agree to comply with the terms outlined in the ViraXpress license.
 *
 * DISCLAIMER
 *
 * Modifications to this file are discouraged to ensure seamless upgrades and compatibility with future releases.
 *
 * @category    ViraXpress
 * @package     ViraXpress_Checkout
 * @author      ViraXpress
 * @copyright   © 2024 ViraXpress (https://www.viraxpress.com/)
 * @license     https://www.viraxpress.com/license
 */

namespace ViraXpress\Checkout\CustomerData;

use Magento\Checkout\CustomerData\DefaultItem as MagentoDefaultItem;

/**
 * Default cart item
 */
class DefaultItem extends MagentoDefaultItem
{

    /**
     * Get item configure url
     *
     * @return string
     */
    protected function getConfigureUrl()
    {
        $options = $this->item->getProduct()->getTypeInstance(true)->getOrderOptions($this->item->getProduct());
        $encodedAttributes = '';

        if ($options) {
            foreach ($options as $option) {
                if (isset($option['super_attribute'])) {
                    $encodedAttributes = base64_encode(json_encode($option['super_attribute']));
                    break;
                }
            }
        }

        $params = [
            'id' => $this->item->getId(),
            'product_id' => $this->item->getProduct()->getId(),
        ];

        if ($encodedAttributes) {
            $params['attr'] = strtr($encodedAttributes, '+/=', '-_.');
        }

        return $this->urlBuilder->getUrl('checkout/cart/configure', $params);
    }
}
