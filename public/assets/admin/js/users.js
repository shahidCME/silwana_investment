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
            // scrollY: true,
            ajax: {
                url:base_url + "getCustomerDataTable",
                method:"POST",
            },
            order: [ [0, 'false'] ],
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
$(".date-picker_dob").datepicker({
    language: "en",
    autoClose: true,
    dateFormat: "dd MM yyyy",
});
$(document).on('click','.add_more',function () {
   var html =  $('#appended').html();
   $('#append_html').append(html);
   $(".date-picker").datepicker({
        language: "en",
        autoClose: true,
        dateFormat: "dd MM yyyy",
    });
});

$(document).on('click','.remove',function () {
    $(this).parent().parent().remove();
});



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

function handleDynamic(){

    errorHandle = 0;
    var val = $("input:checkbox.is_kyc:checked").val();

    if(val == '1'){

        
        $('.name_document').each(function () {
            
            if ($(this).is(':visible')) {
                
                if ($(this).val() == '') {
                    
                    $(this).next('label').html('Please enter document name');
                    
                    errorHandle++;
                    
                } else {
                    
                    $(this).next('label').text('');

            }

        }

        
        
    });
    var extention = ['jpg','png','jpeg','svg','docx','rtf','doc','pdf'];
    $('.document_file').each(function () {
        
        if ($(this).is(':visible')) {
            var fileName = $(this).val();
            ext = fileName.substring(fileName.lastIndexOf('.') + 1);
            if ($(this).val() == '') {
                $(this).next('label').html('Please select document file');
                errorHandle++;
                
            }else if($.inArray(ext, extention) == -1) {
                $(this).next('label').html("Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed");
                errorHandle++;
            }else {
                $(this).next('label').text('');

            }

        }
    });

    $('.edit_file').each(function () {
        
        if ($(this).not(':visible')) {
            if ($(this).val() != '') {
                $(this).prev('label').html('');
                errorHandle--;
                
            }
            if($(this).prev().prev().val() != ''){
                errorHandle++;
            }
            var fileName = $(this).prev().prev().val();
            if(fileName != ''){
                ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                if($.inArray(ext, extention) == -1) {
                    $(this).prev('label').html("Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed");
                    errorHandle++;
                }
            }
            // else {
            //     $(this).next('label').text('');
            //     errorHandle--;

            // }

        }
    });
    
    
    } 
    
    
    return errorHandle;
    
} 
$("#userForm").validate({ 
    rules: {
        fname: { required: true },
        lname: { required: true },
        email: { required: true, email: true },
        password: { required: true },
        country : { required : true },
        nationality : { required : true },
        dob: { 
            required: true,
            date : true
        },
        mobile:{
            required : true,
            number : true
        },
        gender : {
            required : true
        },
        national_id : {
            required : true
        },
        address :{
            required : true
        },
        // country: { required: true },
        date_of_expiry: { required: true },
        nationalIdImage: { 
            required: true, 
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"  
        },
        editnationalIdImage: { 
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
         }
    },
    messages: {
        fname: { required: "Please enter first name" },
        lname: { required: "Please enter last name" },
        email: {
            required: "Email is required ",
            email: "Please enter valid email",
        },
        password : {
            required : "Default password is 123456"
        },
        country : { required : "Please select country" },
        nationality : { required : "Nationality is required" },
        dob: { 
            required: "Please select date of birth " 
        },
        mobile:{
            required : "Please enter mobile"
        },
        gender : {
            required : "Please select gender"
        },
        date_of_expiry: { required: "Please select document date of expiry" },
        nationalIdImage: {
            required: "Please select national id file",
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed",
        },
        editnationalIdImage: {
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed",
        },
    },
    submitHandler: function (form) {
        var valid =  handleDynamic();
        alert(valid);
        if(valid == '0'){
            $('body').attr('disabled','disabled');
            $('#btnSubmit').attr('disabled','disabled');
            $('#btnSubmit').value('please wait');
            $(form).submit();
        }
    }
});