var config = {
    'config': {
        'mixins': {
           'Magento_Checkout/js/view/shipping': {
               'Myvendor_Comments/js/view/shipping-payment-mixin': true
           },
           'Magento_Checkout/js/view/payment': {
               'Myvendor_Comments/js/view/shipping-payment-mixin': true
           }
       }
    }
}
