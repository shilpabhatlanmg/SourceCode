columnarray = [];
columnarray.push("<input type='checkbox' id='bulkDelete'>", "S.No", "Email", "Verified", "created at", "Action");

$(document).ready(function ($) {

    $.ajax({
        url: "/ajax/get-newsletters",
        headers: {'X-CSRF-TOKEN': csrf_token},
        type: 'POST',
        beforeSend: function () {
            $.loader("on");
        },
        data: {columnarray: columnarray},
        "success": function (json) {
            var tableHeaders = '';
            $.each(json.columns, function (i, val) {
                tableHeaders += "<th>" + val + "</th>";
            });

            $("#tableDiv").empty();
            $("#tableDiv").append('<table id="displayTable" class="display" cellspacing="0" width="100%"><thead><tr>' + tableHeaders + '</tr></thead></table>');
            $('#displayTable').dataTable(json);
        },
        complete: function () {

            $.loader("off");
        },
        dataType: "json"
    });
});

$(document).on('click', '.delete-record', function (e) {

    e.preventDefault()
    var id = $(this).attr('id');
    var dataString = 'data_ids=' + id;

    $.confirm({
        title: 'Deleting Confirmation',
        content: 'Are you sure you want to Delete?',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            confirm: {
                text: 'Yes, sure!',
                btnClass: 'btn-warning',
                action: function () {
                    e.preventDefault();

                    $.ajax({
                        type: "POST",
                        url: "/ajax/delete-newsletters",
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        data: dataString,
                        cache: false,
                        beforeSend: function () {
                            $.loader('on');
                        },
                        success: function (data) {
                            var obj = JSON.parse(data);

                            if (obj.type == 'success') {
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);

                            }

                        },
                        complete: function () {
                            $.loader('off');
                        }
                    });

                }
            },
            cancel: function () {
                //$.alert('you clicked on <strong>cancel</strong>');
            },
        }
    });

});


// Bulk Delete jQuery Part
$(document).on('click', '#bulkDelete', function (e) {
    var status = this.checked;
    $(".deleteRow").each(function () {
        $(this).prop("checked", status);
    });
});

// Bulk Send jQuery Part

$('#sendTriger').on("click", function (event) { // triggering delete one by one
    var redirect_url = $(this).attr('data-href');
    if ($(".deleteRow:checked").length < 1) {
        alert('Please select at least 1 row');
        return false;
    }

    if ($('.deleteRow:checked').length > 0) {  // at-least one checkbox checked
        var ids = [];
        $('.deleteRow').each(function () {
            if ($(this).is(':checked')) {
                ids.push($(this).val());
            }
        });
        var ids_string = ids.toString(); // array to string conversion

        $.ajax({
            type: "POST",
            url: "/ajax/send-newsletter",
            headers: {'X-CSRF-TOKEN': csrf_token},
            data: {data_ids: ids_string},
            cache: false,
            beforeSend: function () {
                $.loader('on');
            },
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.type == 'success') {
                    window.location.href = redirect_url;

                }

            },
            complete: function () {
                $.loader('off');
            }
        });
    }
});

function sendNewsLetter(theObj) {
    var res = checkedRow(theObj);
    if (res) {
        $("#sendTriger").trigger('click');
    }
}

function checkedRow(theObj) {
    $('.deleteRow').prop('checked', false);
    var res = $(theObj).parents('tr ').find('.deleteRow').prop('checked', true);
    return res;
}

// Bulk Delete Functional Part
$(document).on('click', '#deleteTriger', function (e) {  // triggering delete one by one
    if ($(".deleteRow:checked").length < 1) {
        alert('Please select at least 1 row');
        return false;
    }

    if ($('.deleteRow:checked').length > 0) {  // at-least one checkbox checked
        var ids = [];
        $('.deleteRow').each(function () {
            if ($(this).is(':checked')) {
                ids.push($(this).val());
            }
        });
        var ids_string = ids.toString(); // array to string conversion

        $.confirm({
            title: 'Deleting Confirmation',
            content: 'Are you sure you want to Delete?',
            animation: 'scale',
            closeAnimation: 'scale',
            opacity: 0.5,
            buttons: {
                confirm: {
                    text: 'Yes, sure!',
                    btnClass: 'btn-warning',
                    action: function () {
                        e.preventDefault();

                        $.ajax({
                            type: "POST",
                            url: "/ajax/delete-newsletters",
                            headers: {'X-CSRF-TOKEN': csrf_token},
                            data: {data_ids: ids_string},
                            cache: false,
                            beforeSend: function () {
                                $.loader('on');
                            },
                            success: function (data) {
                                var obj = JSON.parse(data);

                                if (obj.type == 'success') {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);

                                }

                            },
                            complete: function () {
                                $.loader('off');
                            }
                        });

                    }
                },
                cancel: function () {
                    $(".deleteRow").each(function () {
                        $(this).prop("checked", false);
                    });
                },
            }
        });
    }

});