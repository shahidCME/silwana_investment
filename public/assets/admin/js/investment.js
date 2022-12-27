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

$(document).on('click','.PaymentFile',function(){
    var investment_id = $(this).data('id');
    $('#invest_id').val(investment_id);

});


$(document).ready(function () {
    // DataTable
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    
    $(document).on('change','.change_status',function(){
        var base_url = $("#base_url").val();
        var status = $(this).val();
        var investment_id = $(this).data('investment_id');
        $.ajax({
            url: base_url+"changeStatus",
            method:"post",
            data : {
                status: status,
                investment_id : investment_id
            },
            success: function(res){
                // window.location.reload();
            }
          });
    });


    const base_url = $("#base_url").val();
    $("#InvestmentTable").DataTable({
        processing: true,
        serverSide: true,
        // scrollX:true,
        ajax:{
            url : base_url +"getInvestmentDataTable",
            method:"POST",
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

});

$("#datatable").DataTable();
// $(".date-picker").datepicker({
//     language: "en",
//     autoClose: true,
//     dateFormat: "dd MM yyyy",
//     // minDate: new Date(),
// });

// $(".date-picker-add").datepicker({
//     language: "en",
//     autoClose: true,
//     dateFormat: "dd MM yyyy",
//     minDate: new Date(),
// });

// var c_date = $(".date-picker-edit").val();
// var dateNow = new Date();
//     var diff = func(c_date,dateNow);
//     dateNow.setDate(dateNow.getDate()-diff);
    
// $(".date-picker-edit").datepicker({
//     language: "en",
//     autoClose: true,
//     dateFormat: "dd MM yyyy",
//     minDate: dateNow,
//     // startDate : new Date()
// });
// setTimeout(() => {
//     $(".date-picker-edit").val(c_date);
// }, 1.5);

// function func(c_date,dateNow) {
//     date1 = new Date(c_date);
//     date2 = new Date(dateNow);
//     var milli_secs = date1.getTime() - date2.getTime();
     
//     // Convert the milli seconds to Days 
//     var days = milli_secs / (1000 * 3600 * 24);
//     return Math.floor(Math.abs(days));
// }