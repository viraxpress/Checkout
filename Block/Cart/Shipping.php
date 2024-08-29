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

namespace ViraXpress\Checkout\Block\Cart;

use Magento\Checkout\Model\CompositeConfigProvider;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Serialize\Serializer\JsonHexTag;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Locale\CurrencyInterface;

class Shipping extends \Magento\Checkout\Block\Cart\Shipping
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CompositeConfigProvider
     */
    protected $configProvider;

    /**
     * @var CurrencyInterface
     */
    protected $localeCurrency;

    /**
     * @var TokenFactory
     */
    private $tokenModelFactory;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var \Magento\Framework\Serialize\Serializer\JsonHexTag
     */
    protected $jsonHexTagSerializer;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Checkout\Model\CompositeConfigProvider $configProvider
     * @param TokenFactory $tokenModelFactory
     * @param PriceCurrencyInterface $priceCurrency
     * @param CurrencyInterface $localeCurrency
     * @param array $layoutProcessors
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     * @param \Magento\Framework\Serialize\Serializer\JsonHexTag|null $jsonHexTagSerializer
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\CompositeConfigProvider $configProvider,
        TokenFactory $tokenModelFactory,
        PriceCurrencyInterface $priceCurrency,
        CurrencyInterface $localeCurrency,
        array $layoutProcessors = [],
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        \Magento\Framework\Serialize\Serializer\JsonHexTag $jsonHexTagSerializer = null
    ) {
        $this->tokenModelFactory = $tokenModelFactory;
        $this->priceCurrency = $priceCurrency;
        $this->localeCurrency = $localeCurrency;
        parent::__construct($context, $customerSession, $checkoutSession, $configProvider, $layoutProcessors, $data, $serializer, $jsonHexTagSerializer);
    }

    /**
     * Get Customer token.
     *
     * @return string
     */
    public function getCustomerToken()
    {
        $customerId = $this->_customerSession->getCustomerId();
        
        if ($customerId) {
            $customerToken = $this->tokenModelFactory->create();
            $tokenKey = $customerToken->createCustomerToken($customerId)->getToken();
            return $tokenKey;
        } else {
            return null;
        }
    }

    /**
     * Get current currency code
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        $currencyCode = $this->priceCurrency->getCurrency()->getCurrencyCode();
        $currencySymbol = $this->localeCurrency->getCurrency($currencyCode)->getSymbol();

        return $currencySymbol;
    }

    /**
     * Get current store code
     *
     * @return string
     */
    public function getCurrentStoreCode()
    {
        $currentStoreCode = $this->_storeManager->getStore()->getCode();
        return $currentStoreCode;
    }

    /**
     * Get shipping info
     *
     * @return mixed
     */
    public function getShippingInfo()
    {
        return $this->_checkoutSession->getData('shipping_info');
    }
}
