$(document).ready(function () {

    if($('#userTable').length){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // DataTable
        const base_url = $("#base_url").val();
        $("#userTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url:base_url + "getCustomerDataTable",
                method:"POST",
            },
            columns: [
                { data: "first name" },
                { data: "last name" },
                { data: "email" },
                { data: "mobile" },
                { data: "status" },
                { data: "dob" },
                { data: "action" },
            ],
        });
    }
});

$(document).on('change','#customCheck1',function (){
    if($(this).is(':checked')){
        $('#kycForm').css('display','block');
    }else{
        $('#kycForm').css('display','none');
    }
})
