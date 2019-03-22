$(document).ready(function() {
    // Change the key to your one
    Stripe.setPublishableKey($('input[name="pk"]').val());
    $('#paymentForm')
    .formValidation({
      framework: 'bootstrap',
      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        card_holder_name: {
          validators: {
            notEmpty: {
              message: '<span class="payment-error">The Card holder name is required</span>'
            }
          }
        },
        ccNumber: {
          selector: '[data-stripe="number"]',
          validators: {
            notEmpty: {
              message: '<span class="payment-error">The credit card number is required</span>'
            },
            creditCard: {
              message: '<span class="payment-error">The credit card number is not valid</span>'
            }
          }
        },
        expMonth: {
          selector: '[data-stripe="exp-month"]',
          //row: '.col-xs-3',
          validators: {
            notEmpty: {
              message: '<span class="payment-error">The expiration month is required</span>'
            },
            digits: {
              message: '<span class="payment-error">The expiration month can contain digits only</span>'
            },
            callback: {
              message: '<span class="payment-error2">Expired</span>',
              callback: function(value, validator) {
                value = parseInt(value, 10);
                var year         = validator.getFieldElements('expYear').val(),
                currentMonth = new Date().getMonth() + 1,
                currentYear  = new Date().getFullYear();
                if (value < 0 || value > 12) {
                  return false;
                }
                if (year == '') {
                  return true;
                }
                year = parseInt(year, 10);
                if (year > currentYear || (year == currentYear && value >= currentMonth)) {
                  validator.updateStatus('expYear', 'VALID');
                  return true;
                } else {
                  return false;
                }
              }
            }
          }
        },
        expYear: {
          selector: '[data-stripe="exp-year"]',
          //row: '.col-xs-3',
          validators: {
            notEmpty: {
              message: '<span class="payment-error">The expiration year is required</span>'
            },
            digits: {
              message: '<span class="payment-error">The expiration year can contain digits only</span>'
            },
            callback: {
              message: '<span class="payment-error2">Expired</span>',
              callback: function(value, validator) {
                value = parseInt(value, 10);
                var month        = validator.getFieldElements('expMonth').val(),
                currentMonth = new Date().getMonth() + 1,
                currentYear  = new Date().getFullYear();
                if (value < currentYear || value > currentYear + 100) {
                  return false;
                }
                if (month == '') {
                  return false;
                }
                month = parseInt(month, 10);
                if (value > currentYear || (value == currentYear && month >= currentMonth)) {
                  validator.updateStatus('expMonth', 'VALID');
                  return true;
                } else {
                  return false;
                }
              }
            }
          }
        },
        cvvNumber: {
          selector: '[data-stripe="cvc"]',
          validators: {
            notEmpty: {
              message: '<span class="payment-error">The CVV number is required</span>'
            },
            cvv: {
              message: '<span class="payment-error">The value is not a valid CVV</span>',
              creditCardField: 'ccNumber'
            }
          }
        }
      }
    })
    .on('success.form.fv', function(e) {
      e.preventDefault();
      var $form = $(e.target);
            // Reset the token first
            $form.find('[name="token"]').val('');
            Stripe.card.createToken($form, function(status, response) {
              if (response.error) {
                alert(response.error.message);
              } else {

                var arrData = new Array();
                var form_data = new FormData();

                $('input[name="token"]').val(response.id);

                arrData['token'] = $('input[name="token"]').val();

                if($('input[name="plan_id"]').val() != '' && $('input[name="organization_id"]').val() == ''){
                  
                  arrData['plan_id'] = $('input[name="plan_id"]').val();
                  var action = "admin/subscriptions/payment/process";

                }else if($('input[name="organization_subscription_id"]').val() != '' && $('input[name="organization_id"]').val() == ''){

                  arrData['organization_subscription_id'] = $('input[name="organization_subscription_id"]').val();
                  var action = "admin/subscriptions/card/process";
                  
                } else if($('input[name="plan_id"]').val() != '' && $('input[name="organization_id"]').val() != ''){

                  arrData['organization_id'] = $('input[name="organization_id"]').val();
                  arrData['plan_id'] = $('input[name="plan_id"]').val();
                  var action = "payment/process";

                }

                var item = Object.assign({}, arrData);

                for ( var key in item ) {
                  form_data.append(key, item[key]);
                }


                $.ajax({

                  url: siteURL + action,
                  type: 'POST',
                  processData: false,
                  contentType: false,
                  data: form_data,
                  headers: {'X-CSRF-TOKEN': csrf_token},

                  beforeSend: function () {
                    $.loader("on");
                  },

                  success: function (response) {

                    $('#ConfirmationModal').modal({
                      show: 'true'
                    });

                    if(response.success == true){

                      if(response.plan_id != ''){
                        window.location.href = siteURL + '/thankyou/' + response.plan_id;
                      }

                      $(".confirm_msg").text(response.msg);
                      $form.formValidation('resetForm', true);

                    }
                  },
                  error: function (request, status, error) {

                    $('#ConfirmationModal').modal({
                      show: 'true'
                    });

                    var obj = JSON.parse(request.responseText);

                    $(".confirm_msg").text('')

                    jQuery.each(obj.errors, function(key, value){

                      $(".confirm_msg").append('<p>'+value+'</p>');
                    });

                    $form.formValidation('resetForm', true);

                  },
                  complete: function () {
                    $.loader("off");
                  }
                });

                    /*$form.find('[name="token"]').val(response.id);                 
                    $.ajax({
                        // You need to change the url option to your back-end endpoint
                        url: "{{url('admin/subscriptions/payment/process')}}",
                        data: $form.serialize(),
                        method: 'POST',
                        dataType: 'json'
                      }).success(function(data) {
                        alert(data.msg);                        
                        // Reset the form
                        $form.formValidation('resetForm', true);
                      });*/
                    }
                  });
          });
});