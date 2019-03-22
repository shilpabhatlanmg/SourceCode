$(document).on('click','.poc-remove',function(e){    
 var value = parseInt($(".org-option").val()) - 1;
    $('.org-option').val(value);
    
    e.preventDefault();
                //console.log($(this).parents(".parentss").attr('class'));
                $(this).parents(".parentss").remove();
            });


$(".delete-poc").click(function (e) {

    e.preventDefault()
    var id = $(this).attr('p_id');
    var div = $(this).parents(".parentss");
    var flag = 'poc';
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
                    e.preventDefault();
                    $.removeAjax(id, div, flag);

                }
            },
            cancel: function () {
                    //$.alert('you clicked on <strong>cancel</strong>');
                },
            }
        });
});

$.removeAjax = function (id, div, flg) {

    $.ajax({
        type: 'POST',
        url: siteURL + 'ajax/delete-poc',
        headers: {'X-CSRF-TOKEN': csrf_token},
        data: {
            varID: id,
            flag: flg
        },

        beforeSend: function () {
            $.loader("on");
        },
        success: function (response) {
            var obj = JSON.parse(response);

            if (obj.success == "true") {
                    //location.reload();
                    div.remove();
                }
            },
            complete: function () {
                $.loader("off");
            }
        });

}