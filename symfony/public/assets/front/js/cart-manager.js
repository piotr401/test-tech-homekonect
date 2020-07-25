$(document).ready(function () {
  'use strict';

  function countTotalCart(type, ref) {

    let element = $('.product-amount-'+ref);
    let quantity = parseInt(element.val());
    let price = parseFloat(element.data('price'));

    if (type == 'add') {
      quantity = quantity + 1;
      element.val(quantity);
      $('.product-total-'+ref).text((price * quantity).toFixed(2));
    } else if (type == 'remove') {
      quantity = quantity - 1;
      element.val(quantity);
      $('.product-total-'+ref).text((price * quantity).toFixed(2));
    }

    getTotal();
  }


  // ADD PRODUCT TO CART
  $(document).on('click', '.add-product', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');

      countTotalCart('add', $ref);

      $.ajax({
        url: $('.products-section').data('url'),
        type: 'POST',
        data : {ref: $ref, type: 'add'},
      }).done(function(response){
        if (response.status === 200) {
          console.log('DONE');
        } else {
          console.log('ERROR');
        }
      });
  });


  // REDUCE PRODUCT AMOUNT FROM CART
  $(document).on('click', '.remove-product', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');
    let element = $('.product-amount-'+$ref);
    let quantity = parseInt(element.val());

    if (quantity > parseInt(element.attr('min'))) {
      countTotalCart('remove', $ref);

      $.ajax({
        url: $('.products-section').data('url'),
        type: 'POST',
        data : {ref: $ref, type: 'remove'},
      }).done(function(response){
        if (response.status === 200) {
          console.log('DONE');
        } else {
          console.log('ERROR');
        }
      });
    }
  });

  // DELETE PRODUCT FROM CART
  $(document).on('click', '.delete-product', function (evt) {
    evt.preventDefault();

    let $ref = $(this).data('ref');
    $('.product-'+$ref).remove();
    
    getTotal();

    $.ajax({
      url: $('.products-section').data('url'),
      type: 'POST',
      data : {ref: $ref, type: 'delete'},
    }).done(function(response){
      if (response.status === 200) {
        console.log('DONE');
      } else {
        console.log('ERROR');
      }
    });
  });
  
  function getTotal() {
    let $total = 0;
    $('.product-total').each(function () {
      $total = $total + parseFloat($(this).text());
    });
    $('.cart-total').text($total.toFixed(2));
  }

});