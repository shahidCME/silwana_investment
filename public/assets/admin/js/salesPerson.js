$(document).ready(function () {
    // DataTable
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const base_url = $("#base_url").val();
    $("#salesTable").DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url : base_url +"getSalesPersonDataTable",
            method:"POST",
        },   
        columns: [
            { data: "name" },
            { data: "email" },
            { data: "mobile" },
            { data: "status" },
            { data: "action" },
        ],
    });

});
