jQuery(document).ready(function () {

  $('#joinus').validate({
    debug: true,
    errorClass: 'text-danger client-error',
    errorElement: 'span',
    ignore: ":hidden:not(select)",
    rules: {
      name: {required: true, maxlength:30},
      email: {
        required: {
          depends: function () {
            $(this).val($.trim($(this).val()));
            return true;
          }
        },
        customemail: true
      },
      status: {required: true},
      address: {required: true},
      country_id: {required: true},
      state_id: {required: true},
      city_id: {required: true},
      zip_code: {required:true, minlength: 5},
      phone: {required: true, minlength: 14},
      password: {minlength: 6},
      password_confirmation: {minlength: 6, equalTo: "#passwords"},
      "poc_name[]": {lettersonly:true},
      "poc_contact_no[]": {minlength: 14},
      "poc_email[]": {customemail: true},
      terms_conditions : {required: true},
    },
    submitHandler: function (form) {
        if($('select[name="state_id"]').find(":selected").val()=="" && $('select[name="city_id"]').find(":selected").val()=="" ){
          $('.state_id_error').html("This field is required");
          $('.city_id_error').html("This field is required");
          return false;
        }
        else if($('select[name="state_id"]').find(":selected").val()==""){
          $('.state_id_error').html("This field is required");
         return false;
        }
        else if($('select[name="city_id"]').find(":selected").val()==""){
            $('.city_id_error').html("This field is required");
            return false;
        }
        else{
      form.submit();
        }
    }
  });

  $('#joinus select').on('change', function() {
      $(this).valid();
    });
  
  $('select[name="city_id"]').change(function () {
      if($('select[name="city_id"]').find(":selected").val()!=""){
         $('.city_id_error').html(""); 
      }
  });

//check validation and submit contact form
$('#contactForm').validate({
  debug: true,
  errorClass: 'text-danger client-error',
  errorElement: 'span',
  rules: {
    first_name: { required: true },
    last_name: { required: true },
    email: { customemail: true },
    comment: {required:true}
  },
  submitHandler: function (form) {
   sendcontactemail();
 }
});


function sendcontactemail(){
  ajaxUrl = siteURL + "home",
  arrData = new Array();
  var form_data = new FormData();
  arrData['first_name'] = $("input[name=first_name]").val();
  arrData['last_name'] = $("input[name=last_name]").val();
  arrData['email'] = $("input[name=email]").val();
  arrData['comment'] = $("textarea.comment").val();
  var item = Object.assign({}, arrData);

  for ( var key in item ) {
   form_data.append(key, item[key]);
 }

 $.ajax({

  url: ajaxUrl,
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

      $(".confirm_msg").text(response.msg);
      $('#contactForm').trigger("reset");

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
    
    $('#contactForm').trigger("reset");

  },
  complete: function () {
    $.loader("off");
  }
});

}

//called when key is pressed in textbox
  $(".form-number").keypress(function (e) {
    var errMsg= $(this).attr('data');
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("."+errMsg).html("Numbers Only, Please.").show().fadeOut(3000);
        return false;
      }
    });

});

$.validator.methods.phoneUS = function (value, element) {
  return this.optional(element) || /^(\\(.*\\))(\\s+)(\\d)(\\d)(\\s+)(\\d)(\\d)(\\d)(\\s+)(\\d)(\\d)(\\d)(\\d)$/.test(value);
}


$.validator.addMethod("customemail",
  function (value, element) {
    return this.optional(element) || /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
  },
  "Please enter valid email address"
  );

  /*jQuery.validator.addMethod('customemail', function (emailaddr, element) {
    emailaddr = emailaddr.replace(/\s+/g, '');
    return this.optional(element) || 
    emailaddr.match(/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
  },"Please enter valid email address");*/  

  jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z, " "]+$/i.test(value);
  }, "Letters only please");
