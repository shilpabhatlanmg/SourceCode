jQuery(document).ready(function () {

    $(".organization_id").on('change', function () {

        varStateID = $(this).val();

        if(varStateID == ''){

            $('.major_id').fadeOut(1000);

            return false;
        }


        $('.premise_id option').remove();
        $('.location_id option').remove();
        $('.location_id').append(new Option("--select--", ''));
        //$('.location_id').append(new Option("Add More Location", ''));

        var loc_type = $(this).attr('data-type');

        

        $.ajax({
            url: siteURL + 'admin/getPremise',
            type: 'get',
            data: {
                varStateID: varStateID
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            beforeSend: function () {
                $.loader("on");
            },
            success: function (data) {

                $('.premise_id').append(new Option("--select--", ''));
                
                $(data['premiselist']).each(function (i) {

                    $('.premise_id').append(new Option(data['premiselist'][i].name, data['premiselist'][i].id));


                });

                $('.major_id').show();
                $('.major_id').html('Major ID : ' + data['becaonId']['becon_major_id']);

                /*if(loc_type != 'loc'){
                    $('.premise_id').append(new Option("Add More Premise", ''));
                }*/
                $('#premise_id').data('premisecount', data.premiselist.length);
                $('#location_id').data('locationcount', data.locationCount);
                if(data.premiselist.length == 0){
                    $('#add-premise').hide();
                    $('#list-premise').show();
                    $('#premise_text_id').show();
                    
                    if(loc_type != 'loc'){
                        $('#premise_id').hide();
                    }
                    
                }else{
                    $('#add-premise').show();
                    $('#list-premise').hide();
                    $('#premise_id').show();
                    $('#premise_text_id').hide();
                }

                if(typeof data.locationCount == 'number' && data.locationCount == 0){
                    $('#add-location').hide();
                    $('#list-location').hide();
                    $('#location_text_id').show();
                    $('#location_id').hide();
                }
                
            },
            complete: function () {
                $.loader("off");
            }
        });
    });

    $(".premise_id").on('change', function () {

        var value = $(".premise_id :selected").text();

        if(value == 'Add More Premise'){


            $('#premiseModal').modal({
                show: 'true'
            }); 

            $("#org_premise").val($("#organization_id").val());
            $("#premise_status").val('Active');
            $("#premise_csrf_token").val(csrf_token);
            
            return false;

        }
        
        if($(this).attr('data') == 'location' || $(this).val() == ''){
            return false;
        }

        $.ajax({
            url: siteURL + 'admin/getLocation',
            type: 'get',
            data: {
                varStateID: $(this).val()
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            beforeSend: function () {
                $.loader("on");
            },
            success: function (data) {

                $('.location_id option').remove();
                $('.location_id').append(new Option("--select--", ''));
                $(data).each(function (i) {
                    $('.location_id').append(new Option(data[i].name, data[i].id));
                });
                //$('.location_id').append(new Option("Add More Location", ''));
                $('#location_id').data('locationcount', data.length);
                if(data.length){
                    $('#add-location').show();
                    $('#list-location').hide();
                    $('#location_text_id').val('').hide();
                    $('#location_id').val('').show();
                }else{
                    $('#add-location').hide();
                    $('#list-location').hide();
                    $('#location_id').val('').hide();
                    $('#location_text_id').val('').show().focus();
                }
                
            },
            complete: function () {
                $.loader("off");
            }
        });
    });

    $(".location_id").on('change', function () {

        var value = $(".location_id :selected").text();
        
        if(value == 'Add More Location'){


            $('#locationModal').modal({
                show: 'true'
            }); 

            $("#org_location").val($("#organization_id").val());
            $("#pre_id").val($("#premise_id").val());
            $("#location_status").val('Active');
            $("#location_csrf_token").val(csrf_token);

        }
    });


    $('#becon_primise').validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'span',
        rules: {
          name:{  required: true },
      },

      submitHandler: function (form) {

        var ajxUrl = siteURL + "admin/premiseAdd";
        var assignClass = "premise_id";
        var ModelId = "premiseModal";

        ajaxSentRequest(form, ajxUrl, assignClass, ModelId);

        
    }
});

    $('#becon_location').validate({
        debug: true,
        errorClass: 'text-danger',
        errorElement: 'span',
        rules: {
          name:{  required: true }
      },

      submitHandler: function (form) {

        var ajxUrl = siteURL + "admin/locationAdd";
        var assignClass = "location_id";
        var ModelId = "locationModal";

        ajaxSentRequest(form, ajxUrl, assignClass, ModelId);

        
    }
});

    
    function ajaxSentRequest(form_data, ajxUrl, assignClass, ModelId){

        $.ajax({
                url: ajxUrl, // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: new FormData(form_data), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                headers: {'X-CSRF-TOKEN': csrf_token},
                beforeSend: function () {
                    //$("#load").css("display", "block");
                    $.loader("on");
                }, // To send DOMDocument or non processed data file it is set to false
                success: function (response)   // A function to be called if request succeeds
                {

                    if(response.record.id != ''){


                        $('.' + assignClass + ' option').remove();
                        $('.' + assignClass).append(new Option("--select--", ''));

                        $(response['datalist']).each(function (i) {

                            $('.' + assignClass).append(new Option(response['datalist'][i].name, response['datalist'][i].id));

                            if(response['datalist'][i].id == response.record.id){

                                $('.' + assignClass + ' option').attr("selected", "true");

                            }


                        });

                        if(assignClass == 'premise_id'){

                            //$('.premise_id').append(new Option("Add More Premise", ''));

                            $('.location_id option').remove();
                            $('.location_id').append(new Option("--select--", ''));
                            //$('.location_id').append(new Option("Add More Location", ''));

                        }

                        if(assignClass == 'location_id'){

                            //$('.location_id').append(new Option("Add More Location", ''));
                        }

                        $('#' + ModelId).modal('toggle');

                    }

                },
                error: function (request, status, error) {
                    
                    var obj = JSON.parse(request.responseText);

                    $(".alermsg").text('');

                    jQuery.each(obj.errors, function(key, value){

                        $(".alert-msg").css('display', 'block');
                        $(".alert-msg").addClass('alert alert-danger');
                        $(".alermsg").append('<p>'+value+'</p>');
                        $(".alert-msg").fadeOut(5000);

                    });

                },
                complete: function () {
                    $.loader("off");
                }
            });
    }

    /*$('#premiseModal').on('hidden.bs.modal', function (e) {

      $(this)
      .find("input,textarea,select")
      .val('')
      .end()
      .find("input[type=checkbox], input[type=radio]")
      .prop("checked", "")
      .end();
      if($('#premise_id').find('option:selected').text() == 'Add More Premise')
        $('.premise_id').prop('selectedIndex',0);

  })

    $('#locationModal').on('hidden.bs.modal', function (e) {

      $(this)
      .find("input,textarea,select")
      .val('')
      .end()
      .find("input[type=checkbox], input[type=radio]")
      .prop("checked", "")
      .end();

      if($('#location_id').find('option:selected').text() == 'Add More Location')
        $('.location_id').prop('selectedIndex',0);
  });*/
  
  $('#add-premise').on('click', function(){
    $(this).hide();
    $('#premise_id').val('').hide();
    $('#premise_text_id').show().focus();
    $('#location_id').val('').hide();
    $('#location_text_id').val('').show();
    $('#list-premise').show();
    $('#add-location').hide();
  });
  $('#list-premise').on('click', function(){
    $(this).hide();
    $('#premise_id').show().focus();
    $('#premise_text_id').val('').hide();
    $('#add-premise').show();
  });
  
  $('#add-location').on('click', function(){
    $(this).hide();
    $('#location_id').val('').hide();
    $('#location_text_id').show().focus();
    $('#list-location').show();

  });
  
  $('#list-location').on('click', function(){
    $(this).hide();
    if($('#location_id').data('locationcount') == 0) return;
    $('#location_id').show().focus();
    $('#location_text_id').val('').hide();
    $('#add-location').show();
  });

});