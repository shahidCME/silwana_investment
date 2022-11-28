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
    $("#InvestmentTable").DataTable({
        processing: true,
        serverSide: true,
        scrollX:true,
        ajax:{
            url : base_url +"getInvestmentDataTable",
            method:"POST",
        },
        order: [ [0, 'false'] ],   
        columns: [
            { data: "customer fullname"},
            { data: "sales person"},
            { data: "schema"},
            { data: "start date"},
            { data: "amount"},
            { data: "return type"},
            { data: "status" },
            { data: "action" },
        ],
    });

});

$("#datatable").DataTable({
    scrollX: true,
});