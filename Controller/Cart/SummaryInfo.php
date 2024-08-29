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

namespace ViraXpress\Checkout\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\Result\Json as JsonResult;

class SummaryInfo extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        CheckoutSession $checkoutSession
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Execute action
     */
    public function execute()
    {
        /** @var JsonResult $result */
        $result = $this->jsonFactory->create();

        try {
            $requestData = $this->getRequest()->getContent();
            $data = json_decode($requestData, true);

            $this->checkoutSession->setData('shipping_info', $data);

            $result->setData(['success' => true]);
        } catch (\Exception $e) {
            $result->setData(['success' => false, 'error' => $e->getMessage()]);
        }

        return $result;
    }
}
