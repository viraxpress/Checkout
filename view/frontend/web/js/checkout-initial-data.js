define([
    'uiComponent',
    'jquery',
    'Magento_Checkout/js/checkout-data',
    'Magento_Customer/js/customer-data',
    'Magento_Customer/js/model/customer',
], function (Component, $, checkoutData, customerData, customer) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            const storageKey = 'shipping-checkout-storage';
            var currentShippingData = JSON.parse(localStorage.getItem(storageKey));
            if (currentShippingData && currentShippingData['checkout-data']) {
                if (currentShippingData['checkout-data']['selectedShippingRate'] && currentShippingData['checkout-data']['selectedShippingRate'] != 'null_null') {
                    checkoutData.setSelectedShippingRate(currentShippingData['checkout-data']['selectedShippingRate']);
                }
            }
            if (!customer.isLoggedIn()) {
                if (currentShippingData && currentShippingData['checkout-data']) {
                    if (currentShippingData['checkout-data']['shippingAddressFromData']) {
                        checkoutData.setShippingAddressFromData(currentShippingData['checkout-data']['shippingAddressFromData']);
                    }
                }
            }
        },
    });
});