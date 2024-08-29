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

namespace ViraXpress\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Data\Form\FormKey;

class SetFormKeyObserver implements ObserverInterface
{
    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param Escaper $escaper
     */
    public function __construct(
        FormKey $formKey,
        Escaper $escaper
    ) {
        $this->formKey = $formKey;
        $this->escaper = $escaper;
    }

    /**
     * Setting form key.
     *
     * @param mixed $observer
     */
    public function execute(Observer $observer)
    {
        $response = $observer->getEvent()->getData('response');
        $request = $observer->getEvent()->getData('request');

        // Check if the form_key cookie is not already set
        if (!$request->getCookie('form_key')) {
            $sanitisedFormKey = $this->escaper->escapeHtmlAttr($this->formKey->getFormKey());

            // Set the form key in cookies
            $cookieHeader = 'form_key=' . $sanitisedFormKey . '; path=/; SameSite=Lax;';
            $response->setHeader('Set-Cookie', $cookieHeader, true);
        }
    }
}
