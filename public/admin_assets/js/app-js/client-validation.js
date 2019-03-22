jQuery(document).ready(function () {

   ///////////////for admin login form validation//////////////

    $('#admin_login').validate({// initialize the plugin
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        email: {
          required: {
            depends: function () {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          customemail: true
        },
        password: {required: true}
      },
      submitHandler: function (form) {

        form.submit();
      }
    });

    /*$('#frm_member_signup').validate({// initialize the plugin
     debug: true,
     errorClass: 'text-danger',
     errorElement: 'span',
     rules: {
     title: {required: true},
     fname: {required: true},
     lname: {required: true},
     gender: {required: true},
     mobile: {required: true, number: true},
     email: {required: true},
     password: {required: true, minlength: 6},
     confirm_password: {required: true, minlength: 6, equalTo: "#passwords"},
     terms_conditions: {required: true}
     },
     submitHandler: function (form) {
     form.submit();
     }
   });*/

    $('#admin_forgot_pass').validate({// initialize the plugin
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        email: {
          required: {
            depends: function () {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          customemail: true
        },
      },
      submitHandler: function (form) {

        form.submit();
      }
    });

    ///////////////for laravel reset password form validation//////////////
    $('#admin_reset_password').validate({// initialize the plugin
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        email: {
          required: {
            depends: function () {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          customemail: true
        },
        password: {required: true, minlength: 6},
        password_confirmation: {required: true, minlength: 6, equalTo: "#passwords"},
      },
      submitHandler: function (form) {

        form.submit();
      }
    });

    ///////////////for laravel reset password form validation//////////////
    $('#admin_pass_change').validate({// initialize the plugin
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        email: {
          required: {
            depends: function () {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          customemail: true
        },
        current_password: {required: true, minlength: 6},
        password: {required: true, minlength: 6},
        password_confirmation: {required: true, minlength: 6, equalTo: "#passwords"},
      },
      submitHandler: function (form) {

        form.submit();
      }
    });

    $('#admin_edit_profile').validate({// initialize the plugin
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        name: {required:true, maxlength:30},
        phone: {minlength:14},
        zip_code : {minlength:5},
        //"poc_name[]": {lettersonly:true},
        //"poc_contact_no[]": {minlength: 14},
        //"poc_email[]": {customemail: true},
      },
      submitHandler: function (form) {

        form.submit();
      }
    });

    $('#admin_tetimonial').validate({// initialize the plugin
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        author_image:{
                  //required: true,
                 //accept:"jpeg,jpg,png,gif,pdf,doc,ppt,xls,docx,pptx,xlsx,rar,zip,mp3,mp4"
                 extension: "jpeg,jpg,png,gif"
               },

               content: {required: true},
               author_rating: {required: true, range:[1,5]},
               occupation: {required: true, maxlength:30},
               feedback_date: {required: true},
               author_email: {
                required: {
                  depends: function () {
                    $(this).val($.trim($(this).val()));
                    return true;
                  }
                },
                customemail: true
              },
            },
            messages: {
              author_image:{
                        //required: "Select Image",
                        extension: "The uploaded file type must be a file of type: jpeg,jpg,png,gif"
                      }  
                    },

                    submitHandler: function (form) {

                      form.submit();
                    }
                  });
    
    
    $('#admin_premise').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      ignore: ":hidden:not(select)",
      rules: {
        organization_id: {required: true},
        name:{ required: true, maxlength:30},
        
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#admin_premise select').on('change', function() {
      $(this).valid();
    });

    $('#admin_location').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        organization_id: {required: true},
        premise_id:{  required: true,   },
        name:{ required: true, maxlength:30},
        
      },
      submitHandler: function (form) {
        form.submit();
      }
    });
    
    $('#admin_becon').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        organization_id: {required: true},
        premise_id: {required: true},
        premise_text_id: {required: true, maxlength:30},
        location_text_id: {required: true, maxlength:30},
        location_id: {required: true},
        minor_id: {required: true, minlength: 1, maxlength:9},
        name:{ maxlength:30},
        status: {required: true},
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#admin_subscription').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        plan_name: {required:true, maxlength:30},
        people_allow: {required: true, number: true},
        premises_allow: {required: true, number: true},
        duration: {required: true, number: true},
        type: {required: true},
        price: {required: true, number: true},
        status: {required: true},
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#admin_organization').validate({
      debug: true,
      errorClass: 'text-danger',
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
        //phone: {required: true, pattern: "(\\(.*\\))(\\s+)(\\d)(\\d)(\\s+)(\\d)(\\d)(\\d)(\\s+)(\\d)(\\d)(\\d)(\\d)"},
        phone: {required: true, minlength: 14},
        emergency_contact: {required: true, minlength: 14},
        password: {minlength: 6},
        password_confirmation: {minlength: 6, equalTo: "#passwords"},
        "poc_name[]": {lettersonly:true},
        "poc_contact_no[]": {minlength: 14},
        //"poc_email[]": {customemail: true},
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#admin_organization select').on('change', function() {
      $(this).valid();
    });

    $('#admin_sitesetting').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        address_email: { customemail: true },
        admin_email: { customemail: true },
        from_email: { customemail: true },
        from_name: {lettersonly:true},
        address_zip: {required:true, minlength: 5},
        address_phone: {required: true, minlength: 14},
        facebook_link : {url:true},
        twitter_link : {url:true},
        google_link : {url:true}
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#admin_invite').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        organization_id:{  required: true },
        name:{  required: true, lettersonly : true},
        email: {
          required: {
            depends: function () {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          customemail: true
        },
        contact_number: {required: true, minlength: 14},
        profile_image:{
          extension: "jpeg,jpg,png,gif"
        },
      },

      messages: {
        profile_image:{
          extension: "The uploaded file type must be a file of type: jpeg,jpg,png,gif"
        }  
      },
      submitHandler: function (form) {

        $.ajax({
                url: siteURL + "admin/securityStore", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: new FormData(form), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                beforeSend: function () {
                    //$("#load").css("display", "block");
                    $.loader("on");
                }, // To send DOMDocument or non processed data file it is set to false
                success: function (data)   // A function to be called if request succeeds
                {
                 if(data.success == true){

                  $(".alert-msg").show();
                  $(".alert-msg").removeClass('alert alert-danger');
                  $(".alert-msg").addClass('alert alert-success');
                  $(".alermsg").html(data.msg);
                  $(".alert-msg").fadeOut(5000);

                  setTimeout(function () {
                    location.reload();
                  }, 5000);

                } else if (data.type == 'error') {

                  $(".alert-msg").show();
                  $(".alert-msg").removeClass('alert alert-success');
                  $(".alert-msg").addClass('alert alert-danger');
                  $(".alermsg").html(data.msg);
                  $(".alert-msg").fadeOut(2000);
                }
              },
              error: function (request, status, error) {

                var obj = JSON.parse(request.responseText);

                $(".alermsg").text('');

                jQuery.each(obj.errors, function(key, value){

                  $(".alert-msg").css('display', 'block');
                  $(".alert-msg").addClass('alert alert-danger');
                  $(".alermsg").append('<p>'+value+'</p>');
                  
                });

                //$(".alert-msg").fadeOut(5000);

                
              },
              complete: function () {
                $.loader("off");
              }
            });
      }
    });
    
    
    $('#admin_user').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      rules: {
        username :{required: true, minlength: 2},
        email: { required:true,customemail: true },
        address_zip: {required:true, minlength: 5},
        address_phone: {required: true, minlength: 14},
        password: {required: true,minlength: 6},
        password_confirmation: {required: true,minlength: 6, equalTo: "#passwords"},
        
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#sub_admin').validate({
      debug: true,
      errorClass: 'text-danger',
      errorElement: 'span',
      ignore: ":hidden:not(select)",

      rules: {
        name :{required: true, maxlength: 30},
        email: { required:true,customemail: true },
        street: { required:true },
        country_id: { required:true },
        state_id: { required:true },
        city_id: { required:true },
        address_zip: {required:true, maxlength: 5},
        address_phone: {required: true, minlength: 14},
        password: {required: true,minlength: 6},
        password_confirmation: {required: true,minlength: 6, equalTo: "#passwords"},
        
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

    $('#sub_admin select').on('change', function() {
      $(this).valid();
    });

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