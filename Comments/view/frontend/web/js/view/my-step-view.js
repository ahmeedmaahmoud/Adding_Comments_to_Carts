define([
    'ko',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/shipping-save-processor',
    'mage/url',
    'jquery' // Ensure jQuery is loaded
], function (ko, Component, _, stepNavigator, customerData, setShippingInformationAction, shippingSaveProcessor, url, $) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Myvendor_Comments/commentstep'
        },

        isVisible: ko.observable(true),
        userComment: ko.observable(''),

        initialize: function () {
            this._super();
            stepNavigator.registerStep(
                'comments_notes',
                null,
                'Comments/Notes',
                this.isVisible,
                _.bind(this.navigate, this),
                15
            );
            return this;
        },

        navigate: function () {
            this.isVisible(true);
        },

        navigateToNextStep: function () {
            var comment = this.userComment();
            var self = this;

            // Save comment in the customer's session for use during checkout
            customerData.set('checkout-comment', comment);

            // Save shipping information
            shippingSaveProcessor.saveShippingInformation().done(function () {
                // Send comment data to the custom controller
                var saveCommentUrl = url.build('comments/checkout/savecomment');
                $.ajax({
                    url: saveCommentUrl,
                    type: 'POST',
                    data: {
                        comment: comment
                    },
                    success: function () {
                        // Proceed to the next step
                        setShippingInformationAction().done(function () {
                            stepNavigator.next();
                        });
                    },
                    error: function () {
                        // Handle error if needed
                    }
                });
            });
        }
    });
});
