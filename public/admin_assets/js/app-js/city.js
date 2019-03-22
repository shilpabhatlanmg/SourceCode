jQuery(document).ready(function () {

    $(".state_id").change(function () {
        
        var flag = $(this).attr('data-type');
        var StateID = $(this).val();
        $('.address_city_id option').remove();
        $('.state_id_error').html('');
        $.ajax({
            url: siteURL + 'ajax/getCity',
            type: 'get',
            data: {
                varStateID: StateID
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            beforeSend: function () {
                $.loader("on");
            },
            success: function (data) {
                if(flag=='city-not-required'){
                $('.address_city_id').append(new Option("Select city", ''));
            }
            else{
              $('.address_city_id').append(new Option("Select city *", ''));  
            }
                $(data).each(function (i) {
                    $('.address_city_id').append(new Option(data[i].name, data[i].id));
                });

                if(flag == 'front'){
                    
                    $('.address_city_id').selectMania('update');

                }else {
                    
                    $(".address_city_id").addClass("chosen-select").change();
                    $('.address_city_id').trigger('chosen:updated');
                }
            },
            complete: function () {
                $.loader("off");
            }
        });
    });
});