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

declare(strict_types=1);

namespace ViraXpress\Checkout\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Cart;

class ProductQty extends Action
{

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Cart $cart
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Cart $cart
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->cart = $cart;
    }

    /**
     * Execute action and return the cart details result
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $productId = (int) $this->getRequest()->getParam('product_id');

        $productQty = 0;
        foreach ($this->cart->getQuote()->getAllItems() as $item) {
            if ($item->getProductId() == $productId) {
                $productQty += (int) $item->getQty();
            }
        }

        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();
        $result->setData(['product_qty' => $productQty]);

        return $result;
    }
}
