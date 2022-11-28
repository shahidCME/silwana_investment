function readURL(input) {
    var reader = new FileReader();
        reader.onload = function (e) {
            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();
        };
        reader.readAsDataURL(input.files[0]);
}

$(document).ready(function () {
    // DataTable
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const base_url = $("#base_url").val();
    $("#UserInvestmentTable").DataTable({
        processing: true,
        serverSide: true,
        // scrollX : true,
        ajax:{
            url : base_url +"getUserInvestmentDataTable",
            method:"POST",
        },   
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
