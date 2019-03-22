//columnarray = [];
//columnarray.push("S.No", "Contact Numebr", "Premises", "Location", "Time/Date");

$(document).ready(function ($) {

    /*var table = $('#example').DataTable( {
        "ajax": "/admin/getAidRequest",
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "sno" },
            { "data": "contact" },
            { "data": "name" },
            { "data": "location" },
            { "data": "date/time" },



        ],
        "order": [[1, 'asc']]
    } );
     

    $('#example tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );*/

    // var dt = $('#example').DataTable( {
    //     "processing": true,
    //     "serverSide": false,
    //     "ajax": "/admin/getAidRequest",

    //     "columns": [
    //         {
    //             "class":          "details-control",
    //             "orderable":      false,
    //             "data":           null,
    //             "defaultContent": ""
    //         },
    //         { "data": "sno" },
    //         { "data": "contact" },
    //         { "data": "name" },
    //         { "data": "location" },
    //         { "data": "date/time" }
    //     ],
    //     "order": [[1, 'asc']]
    // } );
 
    // Array to track the ids of the details displayed rows
    var detailRows = [];
 
    $('#aid-requests').find('td').on( 'click', function () { 
        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/++[++^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
        var tr = $(this).closest('tr');
        var row = Base64.decode(tr.data('security').trim());
        console.log('HHHH', JSON.parse(row));
        // var idx = $.inArray( tr.attr('id'), detailRows );
 
        // if ( row.child.isShown() ) {
        //     tr.removeClass( 'details' );
        //     row.child.hide();
 
        //     // Remove from the 'open' array
        //     detailRows.splice( idx, 1 );
        // }
        // else {
        //     tr.addClass( 'details' );
        //     row.child( format( row.data() ) ).show();
 
        //     // Add to the 'open' array
        //     if ( idx === -1 ) {
        //         detailRows.push( tr.attr('id') );
        //     }
        // }
    } );
 
    // On each draw, loop over the `detailRows` array and show any child rows
    // dt.on( 'draw', function () {
    //     $.each( detailRows, function ( i, id ) {
    //         $('#'+id+' td.details-control').trigger( 'click' );
    //     } );
    // } );


    /*$.ajax({
        url: "/admin/getAidRequest",
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
    });*/
});

/*$(document).on('click', '.delete-record', function (e) {

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

                            if (obj.type == 'delete') {
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

function confirm_status(sts, id) {

    var dataString = 'id=' + id + '&sts=' + sts;

    if (id) {
        $.ajax({
            type: "POST",
            url: "/ajax/change-status",
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
                $.loader("off");
            }
        });
    }
}*/

/* Formatting function for row details - modify as you need */
/*function format ( d ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Name:</td>'+
            '<td>'+d.response_name+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Reaction Date/Time:</td>'+
            '<td>'+d.restime+'</td>'+
        '</tr>'+
    '</table>';
}*/

function format ( d ) {
    return 'Full name: '+d.response_name+'<br>'+
        'Reaction Date/Time: '+d.restime+'<br>';
}