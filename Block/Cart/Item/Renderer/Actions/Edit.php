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

namespace ViraXpress\Checkout\Block\Cart\Item\Renderer\Actions;

/**
 * @api
 * @since 100.0.2
 */
class Edit extends \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Edit
{
    /**
     * Get item configure url
     *
     * @return string
     */
    public function getConfigureUrl()
    {
        $item = $this->getItem();
        $product = $item->getProduct();
        $typeInstance = $product->getTypeInstance(true);
        $options = $typeInstance->getOrderOptions($product);

        if ($options) {
            foreach ($options as $option) {
                if (isset($option['super_attribute'])) {
                    $encodedAttributes = base64_encode(json_encode($option['super_attribute']));
                    $encodedAttributes = strtr($encodedAttributes, '+/=', '-_.');

                    return $this->getUrl(
                        'checkout/cart/configure',
                        [
                            'id' => $item->getId(),
                            'product_id' => $product->getId(),
                            'attr' => $encodedAttributes
                        ]
                    );
                }
            }
        }

        return $this->getUrl(
            'checkout/cart/configure',
            [
                'id' => $item->getId(),
                'product_id' => $product->getId()
            ]
        );
    }
}
