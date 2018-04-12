<? if(ENV != 'PROD'): ?>

  <!-- This code should be called via google tag manager-->
  <script type="text/javascript">
      $(document).ready(function () {
          var dataLayer = window.dataLayer || [];

          if ($('.js-dl-product').length > 0) {
              var impressions = [];
              $('.js-dl-product').each(function () {
                  impressions.push($(this).data());
                  $(this).click(function () {
                      processProductClickEvent($(this).data());
                  });
              });
              processProductImpressions(impressions);
          }

          if ($('.js-product-view').length > 0) {
              processProductDetailImpressions($('.js-product-view').data());
          }

          if ($('.js-remove-from-cart').length > 0) {
              $('.js-remove-from-cart').click(function () {
                  var productNode = $(this).parents('.js-tr');
                  if (productNode.length > 0) {
                      preRemoveFromCartEvent(_getProductObjDataOnCheckout(productNode));
                  }
              });
          }

          if ($('.js-order-data').length > 0) {
              var products = [];
              $('.js-order-data .js-tr').each(function () {
                  products.push(_getProductObjDataOnCheckout($(this)));
              });
              processOnCheckoutEvent(products, 2);

              $('#order_form').submit(function() {
                  if ($(this).valid()) {
                      processOnCheckoutEvent(products, 3);
                  }
              });
          }
      });

      function processProductImpressions(impressions) {
          if (impressions.length > 0) {
              dataLayer.push({
                  'ecommerce': {
                      'currencyCode': 'UAH',
                      'impressions': impressions
                  }
              });
          }
      }
      function processProductDetailImpressions(productObj) {
          dataLayer.push({
              'ecommerce': {
                  'detail': {
                      'products': [{
                          'name': productObj.name,
                          'id': productObj.id,
                          'price': productObj.price,
                          'brand': productObj.brand,
                          'category': productObj.category,
                      }]
                  }
              }
          });
      }
      function processProductClickEvent(productObj) {
          dataLayer.push({
              'event': 'productClick',
              'ecommerce': {
                  'click': {
                      'products': [{
                          'name': productObj.name,
                          'id': productObj.id,
                          'price': productObj.price,
                          'brand': productObj.brand,
                          'category': productObj.category,
                          'position': productObj.position
                      }]
                  }
              },
              'eventCallback': function() {
                  document.location = productObj.url
              }
          });
      }
      function preAddToCartEvent(productObj) {
          productObj.quantity = 1;
          dataLayer.push({
              'event': 'addToCart',
              'ecommerce': {
                  'currencyCode': 'UAH',
                  'add': {
                      'products': [{
                          'name': productObj.name,
                          'id': productObj.id,
                          'price': productObj.price,
                          'brand': productObj.brand,
                          'category': productObj.category,
                          'quantity': productObj.quantity
                      }]
                  }
              }
          });
          processOnCheckoutEvent([productObj], 1);
      }
      function preRemoveFromCartEvent(productObj) {
          dataLayer.push({
              'event': 'removeFromCart',
              'ecommerce': {
                  'remove': {
                      'products': [{
                          'name': productObj.name,
                          'id': productObj.id,
                          'price': productObj.price,
                          'brand': productObj.brand,
                          'category': productObj.category,
                          'quantity': productObj.quantity
                      }]
                  }
              }
          });
      }
      function processOnCheckoutEvent(products, step) {
          var options = {
              1: 'Purchase button click',
              2: 'Checkout form render',
              3: 'Checout button click'
          };
          dataLayer.push({
              'event': 'checkout',
              'ecommerce': {
                  'checkout': {
                      'actionField': {'step': step, 'option': options[step]},
                      'products': products
                  }
              },
              'eventCallback': function() {
                  //
              }
          });
      }
      function _getProductObjDataOnCheckout(trElem) {
          var productObj = trElem.data();
          productObj.quantity = trElem.find('.js-qty').val();
          productObj.price = trElem.find('.js-real-price').text();
          return productObj;
      }
  </script>

<? endif; ?>