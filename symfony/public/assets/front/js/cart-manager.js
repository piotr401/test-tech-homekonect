$(document).ready(function () {
  'use strict';

  let Ttotal = 0;
  function countTotalCart(type, ref, quantity) {
    let $element = $('.product-data-'+ref);
    let $amount = $element.data('amount');
    if (type == 'add') {
      if (quantity > 0) {
        $element.data('amount', quantity);
      } else {
        $element.data('amount', $amount+1);
      }
    } else if (type == 'remove') {
      $element.data('amount', $amount-1);
    }
    if (type != 'count') {
      $('.total-price-' + ref).text(($element.data('price') * $element.data('amount')).toFixed(2) + ' €');
      $('.product-cart-amount-'+ ref).text($element.data('amount'));
    }

    let $total = 0;
    $('.product-data').each(function () {
      $total = $total + ($(this).data('price') * $(this).data('amount'));
    });

    $('.cart-subtotal').text($total.toFixed(2) + ' €');

    let shipping = 0;
    let startPrice = 0;
    if (typeof $element.data('deliveryprice') !== 'undefined') {
      shipping = $element.data('deliveryprice');
      startPrice = $element.data('deliverystart');
    }

    let cart_total = 0;
    if ($total >= startPrice) {
      $('.cart-shipping').text('Gratuite');
      cart_total = $total;
    } else {
      $('.cart-shipping').text(shipping + ' €');
      cart_total = $total + shipping;
    }

    if (typeof $element.data('promotype') !== 'undefined') {
      if ($element.data('promotype') == 'percent') {
        let $promo = parseInt($element.data('promoamount'));
        Ttotal = (cart_total - (cart_total * ($promo/100))).toFixed(2);
        $('.cart-discount').text('-'+$promo + ' %');
      } else {
        let $promo = parseInt($element.data('promoamount'));
        Ttotal = cart_total - $promo;
        $('.cart-discount').text('-'+$promo + ' €');
      }
      $('.cart-total').text(Ttotal.toFixed(2));
      $('.code-promo-code').remove();
    } else {
      $('.cart-total').text(cart_total.toFixed(2));
    }
  }

  $(document).on('change', '.product-amount', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');
    let $amount =  $('.product-amount-'+$ref).val();

    countTotalCart('add', $ref, $amount);

    $.ajax({
      url: $('.products-section').data('url'),
      type: 'POST',
      data : {ref: $ref, type: 'set', amount: $amount},
    }).done(function(response){
      if (response.status === 200) {
        console.log('DONE');
        $('.cart-amount').text(response.totalAmount);
      } else {
        console.log('ERROR');
      }

    });
  });

  // ADD PRODUCT TO CART
  $(document).on('click', '.add-to-cart', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');

    countTotalCart('add', $ref, 0);

    let $amount =  $('.product-amount-'+$ref).val();
    if (typeof $amount === "undefined") {
      $amount = 1;
    }
    $.ajax({
      url: $('.products-section').data('url'),
      type: 'POST',
      data : {ref: $ref, type: 'add', amount: $amount},
    }).done(function(response){
      if (response.status === 200) {
        console.log('DONE');
        $('.cart-amount').text(response.totalAmount);
      } else {
        console.log('ERROR');
      }

    });
  });

  // REDUCE PRODUCT AMOUNT FROM CART
  $(document).on('click', '.remove-cart-product', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');

    countTotalCart('remove', $ref, 0);

    $.ajax({
      url: $('.products-section').data('url'),
      type: 'POST',
      data : {ref: $ref, type: 'remove', amount: 0},
    }).done(function(response){
      if (response.status === 200) {
        console.log('DONE');
        $('.cart-amount').text(response.totalAmount);
      } else {
        console.log('ERROR');
      }
    });
  });

  // DELETE PRODUCT FROM CART
  $(document).on('click', '.remove-from-cart', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');
    $('.cart-product-'+$ref).remove();
    let $count = $('.cart-products').length;
    if ($count <= 0) {
      $('.cart-form').remove();
    }
    $.ajax({
      url: $('.products-section').data('url'),
      type: 'POST',
      data : {ref: $ref, type: 'delete', amount: 0},
    }).done(function(response){
      if (response.status === 200) {
        if (response.subTotal >= response.deliveryMin) {
          $('.cart-shipping').text('Gratuite');
        } else {
          if (response.deliveryPrice > 0) {
            $('.cart-shipping').text(response.deliveryPrice + ' €');
          } else {
            $('.cart-shipping').text('Gratuite');
          }
        }
        $('.cart-subtotal').text(response.subTotal + ' €');
        $('.cart-total').text(response.total);
        $('.total-price-'+$ref).text(response.prodAmount + ' €');
        $('.product-cart-amount-' + $ref).text(response.amount);
        $('.cart-amount').text(response.totalAmount);
      } else {
        console.log('ERROR');
      }

    });

  });

  let lock = false;
  $(document).on('click', '.validate-code-promo', function (e) {
    e.preventDefault();

    let $code = $('.code-promo-code').val();

    if ($code != '' && $code != null && lock == false) {
      lock = true;
      $.ajax({
        url: $(this).attr('href'),
        type: 'POST',
        data : {code: $code, amount: $('.cart-subtotal').data('total')},
      }).done(function(response){
        lock = false;
        if (response.code != null) {

          $('.product-data').each(function () {
            $(this).data('promotype', response.type);
            $(this).data('promoamount', response.amount);
          });
          if (response.type == 'percent') {
            $('.cart-discount').text('-'+response.percentage + ' %');
          } else {
            $('.cart-discount').text('-'+response.amount + ' €');
          }
          $('.promo-section').remove();

          countTotalCart('count', 0, 0);
        } else {
          $('.code-promo-code').css('border-color', 'red');
        }
      });
    }
  });
});