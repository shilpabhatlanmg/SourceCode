jQuery(document).ready(function () {

    $(".delete-record").click(function (e) {

        e.preventDefault()
        var id = $(this).attr('id');
        var datamsg = $(this).attr('data-msg');
        var msg = 'Are you sure you want to Delete?';
        if(datamsg == 'org'){
            var msg = "Are you sure you want to delete the Organization ?<br>All the organization data will be deleted permanently and this cannot be undone.";
        }

        $.confirm({
            title: 'Deleting Confirmation',
            content: msg,
            animation: 'scale',
            closeAnimation: 'scale',
            opacity: 0.5,
            buttons: {
                confirm: {
                    text: 'Yes, sure!',
                    btnClass: 'btn-orange',
                    action: function () {
                        e.preventDefault();
                        document.getElementById('delete-form-' + id).submit();
                    }
                },
                cancel: function () {
                    //$.alert('you clicked on <strong>cancel</strong>');
                },
            }
        });
    });

    
    $(document).on('click', '.sts', function(e){

        var data_type = $(this).attr("data-type");

        if(data_type == "subs"){

            actionUrl = siteURL + "admin/subscriptionStatus";

        }  else if(data_type == "premise"){

            actionUrl = siteURL + "admin/premiseStatus";

        } else if(data_type == "location"){

            actionUrl = siteURL + "admin/locationStatus";

        } else if(data_type == "becon"){

            actionUrl = siteURL + "admin/beconStatus";

        } else if(data_type == "testimonial"){

            actionUrl = siteURL + "admin/testimonialStatus";

        } else if(data_type == "org"){
            actionUrl = siteURL + "admin/organizationStatus";
            
        } else if(data_type == "security"){
            actionUrl = siteURL + "admin/securityStatus";
        }
         else if(data_type == "admin-user"){
            actionUrl = siteURL + "admin/adminUserStatus";
        }
        

        var arrData = new Array();
        arrData['id'] = $(this).attr("data-id");
        arrData['sts'] = $(this).attr("data-sts");

        var item = Object.assign({}, arrData);

        var form_data = new FormData();

        for ( var key in item ) {
            form_data.append(key, item[key]);
        }

        ajaxSentRequest(form_data, actionUrl, $(this));

    });

});

$(".reinvite").click(function (e) {

    ajaxUrl = siteURL + "admin/reInvite";

    var arrData = new Array();
    var form_data = new FormData();

    arrData['id'] = $(this).attr("data-id");;
    var item = Object.assign({}, arrData);

    for ( var key in item ) {
        form_data.append(key, item[key]);
    }

    ajaxSentRequest(form_data, ajaxUrl);
});

$(".resendActivationMail").click(function (e) {

    ajaxUrl = siteURL + "admin/resendMail";

    var arrData = new Array();
    var form_data = new FormData();

    arrData['id'] = $(this).attr("data-id");
    var item = Object.assign({}, arrData);

    for ( var key in item ) {
        form_data.append(key, item[key]);
    }

    ajaxSentRequest(form_data, ajaxUrl);
});

$(".cancel_subscription").click(function (e) {

    ajaxUrl = siteURL + "admin/cancelSubscription",

    arrData = new Array();
    var form_data = new FormData();

    arrData['id'] = $(this).attr("data-id");
    var item = Object.assign({}, arrData);

    for ( var key in item ) {
        form_data.append(key, item[key]);
    }

    $.confirm({
        title: 'Canceling Confirmation',
        content: 'Are you sure you want to Cancel Subscription?',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            confirm: {
                text: 'Yes, sure!',
                btnClass: 'btn-orange',
                action: function () {
                    ajaxSentRequest(form_data, ajaxUrl);
                }
            },
            cancel: function () {
                    //$.alert('you clicked on <strong>cancel</strong>');
                },
            }
        });

    
});

$(".setDefaultPayment").click(function (e) {

    ajaxUrl = siteURL + "admin/defaultCardPayment",

    arrData = new Array();
    var form_data = new FormData();

    arrData['id'] = $(this).attr("data-id");
    arrData['cust_id'] = $(this).attr("cust-id");
    var item = Object.assign({}, arrData);

    for ( var key in item ) {
        form_data.append(key, item[key]);
    }

    ajaxSentRequest(form_data, ajaxUrl, $(this));

    
});




$(".delete_card").click(function (e) {

    ajaxUrl = siteURL + "admin/deleteCard",

    arrData = new Array();
    var form_data = new FormData();

    arrData['id'] = $(this).attr("data-id");
    arrData['cust_id'] = $(this).attr("cust-id");
    var item = Object.assign({}, arrData);

    for ( var key in item ) {
        form_data.append(key, item[key]);
    }

    $.confirm({
        title: 'Deleting Confirmation',
        content: 'Are you sure you want to Delete?',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            confirm: {
                text: 'Yes, sure!',
                btnClass: 'btn-orange',
                action: function () {
                    ajaxSentRequest(form_data, ajaxUrl);
                }
            },
            cancel: function () {
                    //$.alert('you clicked on <strong>cancel</strong>');
                },
            }
        });

    
});

function ajaxSentRequest(form_data, ajxUrl, thisElement){


    $.ajax({

        url: ajxUrl,
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


                if(response.sts != '' && response.sts == 'Inactive'){

                    thisElement.removeClass('label-success').addClass('label-danger').text(response.sts).attr('data-sts', 'Active').attr('title', 'click to make Active');
                //$('.status'+response.id).html('<span class="label label-danger sts" title="Click to make Active" style="cursor: pointer;" data-sts="Active" data-id="'+response.id+'" data-type="'+dataType+'">'+response.sts+'</span>');

                if(thisElement.attr('data-type') == 'org'){



                    $(".reasons" + thisElement.attr('rel-id')).text(response.reason);

                }

            }

            if(response.sts != '' && response.sts == 'Active'){



                if(response.invit_sts != '' && response.invit_sts == 'complete') {

                    $(".invit_sts" + thisElement.attr('rel-id')).removeClass('label-danger').addClass('label-success');

                    $(".invit_sts" + thisElement.attr('rel-id')).text(response.invit_sts);

                }


                thisElement.removeClass('label-danger').addClass('label-success').text('Active').attr('data-sts', 'Inactive').attr('title', 'click to make Inactive');
        //$('.status'+response.id).html('<span class="label label-success sts" title="Click to make Inactive" style="cursor: pointer;" data-sts="Inactive" data-id="'+response.id+'" data-type="'+dataType+'">'+response.sts+'</span>');

        if(thisElement.attr('data-type') == 'org'){
            $(".reasons" + thisElement.attr('rel-id')).text('---');
            
        }


    }

    $(".confirm_msg").text(response.msg);

    if((response.responseType != '' && response.responseType == 'delete') || (response.responseType == 'defaultCard')){
        window.location.reload();

    }

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

},
complete: function () {
    $.loader("off");
}
});
}
