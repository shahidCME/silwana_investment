function readURL(input) {
    var reader = new FileReader();
        reader.onload = function (e) {
            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();
        };
        reader.readAsDataURL(input.files[0]);
}

$(document).on('click','.payBtn',function(){
    var val = $(this).data('id');
    $('#roi_id').val(val);
});

$(document).on('click','.CancelContract',function(){
    var investment_id = $(this).data('id');
    $('#investment_id').val(investment_id);

});


$(document).ready(function () {
    // DataTable
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const base_url = $("#base_url").val();
var ReportTable = $("#ReportTable").DataTable({
            processing: true,
            serverSide: true,
            // scrollX:true,
            ajax:{
                url : base_url+"getReportDataTable",
                method:"POST",
            },
            order: [ [0, 'false'] ],   
            dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excel',
                    exportOptions: {
                        columns: ':not(.datatable-nosort)'
                    }
                }
            ],
            columns: [
                { data: "client"},
                { data: "sales"},
                { data: "schema"},
                { data: "start date"},
                { data: "end date"},
                { data: "amount"},
                { data: "roi"},
                { data: "status" },
                { data: "action" },
            ]
        });

});

$(document).on('click','#btnSubmit',function() {
    var user_id = $('#customer').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    fn_dataTable(user_id,start_date,end_date);

})

function fn_dataTable(user_id,start_date,end_date){
    var url =  $("#base_url").val()
    $("#ReportTable").DataTable({
        destroy: true, 
        processing: true,
        serverSide: true,
        dom: 'Blfrtip',
        buttons: [
            {
                extend : 'excel',
                exportOptions: {
                    columns: ':not(.datatable-nosort)'
                }
            }
        ],
        ajax:{
            url : url+"getReportDataTable",
            type:"POST",
            data : { 
                user_id : user_id,
                start_date : start_date,
                end_date :  end_date,
            },
        },
        order: [ [0, 'false'] ],   
        columns: [
            { data: "client"},
            { data: "sales"},
            { data: "schema"},
            { data: "start date"},
            { data: "end date"},
            { data: "amount"},
            { data: "roi"},
            { data: "status" },
            { data: "action" },
        ],
    });
    // $('#videoTable').DataTable({
    //       "destroy": true, 
    //       'processing': true,
    //       'serverSide': true,
    //       'serverMethod': 'post',
    //         'order': [[ 0, "desc" ]],
    //       'ajax': {
    //          'url': base_url+'/admin/video/dataTable',
    //          'data' : { 
    //             category_id : $('#filterChannel').val(),
    //             fetured : fetured,
    //             creator : creator
    //         },
    //         },
    //       'columns': [
    //          { data: 'video_id' },
    //          { data: 'category_name' },
    //          { data: 'title' },
    //          { data: 'img' },
    //          { data: 'status' },
    //          { data: 'action' },
    //       ]
    //     });
} 
// $("#datatable").DataTable();