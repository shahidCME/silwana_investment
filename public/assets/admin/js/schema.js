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
            url : base_url +"getSchemaDataTable",
            method:"POST",
        },  
        order: [ [0, 'false'] ],
        columns: [
            { data: "name" },
            { data: "type" },
            { data: "start_date" },
            { data: "image" },
            { data: "status" },
            { data: "action" },
        ],
    });

});
function readURL(input) {
    var reader = new FileReader();
        reader.onload = function (e) {
            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();
        };
        reader.readAsDataURL(input.files[0]);
}