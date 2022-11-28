const base_url = $("#base_url").val();
$(document).ready(function () {

    if($('#userTable').length){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // DataTable
        $("#userTable").DataTable({
            processing: true,
            serverSide: true,
            scrollY: true,
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

$(document).on('change','#country_id',function () {
   var country_id = $(this).val();
   $.ajax({
        url:base_url+'gerNationality',
        data:{country_id:country_id},
        dataType:"json",
        success:function(output){
            if(output.status == '1'){
                $('#nationality').val(output.nationality);
            }
            console.log(output);
        }
   })
})
