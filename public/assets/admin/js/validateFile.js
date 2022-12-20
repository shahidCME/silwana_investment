$("#schemaForm").validate({
    rules: {
        name: { required: true },
        type: { required: true },
        start_date :  { required : true },
        image : { required: true,
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf" 
                },
        editimage: { 
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
            },
        schema_document : {
            required : true,
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
        },
        edit_schema_document : {
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
         }
    },
    messages: {
        name: { required: "Please enter schema name" },
      
        email: {
            required: "email is required ",
            email: "Please enter valid email",
        },
        dob: { required: "Please select date of birth " },
        country: { required: "Please select country" },
        image: {
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed",
        },
        editimage: {
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed",
        },
        schema_document : {
            required : "Please select schema document",
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"
        },
        edit_schema_document : {
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"
        },
        

    },
});



$("#salesPerson").validate({
    rules: {
        fname: { required: true },
        lname: { required: true },
        email: { required: true, email: true },
        password: { required: true },
        dob: { 
            required: true,
            date : true
        },
        mobile:{
            required : true,
            number : true
        },
        role : {
            required : true
        },
        status : {
            required : true
        },
        country_code: { required: true },
        // userprofile: { required: true, accept: "jpg,png,jpeg,gif" },
        // edituserprofile: { accept: "jpg,png,jpeg,gif" },
        // "hobby[]": { required: true },
    },
    messages: {
        fname: { required: "First Name is required " },
        lname: { required: "Lats Name is required " },
        email: {
            required: "Email is required ",
            email: "Please enter valid email",
        },
        password : {
            required : "Default password is 123456"
        },
        mobile:{
            required : "Please enter mobile"
        },
        role : {
            required : "Please select role"
        },
        status : {
            required : "Please select status"
        },
        country_code: { required: "Please select country code" },
        // userprofile: {
        //     required: "Please select userprofile",
        //     accept: "Only image type jpg/png/jpeg/gif is allowed",
        // },
        // edituserprofile: {
        //     accept: "Only image type jpg/png/jpeg/gif is allowed",
        // },
    },
    submitHandler: function (form) {

        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});


$("#investmentForm").validate({
    rules: {
        customer: { required: true },
        schema: { required: true },
        tenure: { 
            required: true,
            number : true
        },
        amount: { 
            required: true,
            number:true
        },
        return_type:{
            required : true
        },
        start_date:{
            required : true,
        },
        return_percentage : {
            required : true,
            min:1,
            max :100
        },
        status : {
            required : true
        },
        contract : {
            required : true
        },
        contract_reciept: { 
            required: true, 
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf" 
        },
        edit_contract_reciept: { 
            accept: "jpg|png|jpeg|svg|docx|rtf|doc|pdf" 
        },
        
        other_document: { 
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
        },
        edit_other_document: { 
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
        },
        invest_document : {
            required : true,
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
        },
        edit_invest_document : {
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
        }
    },
    messages: {
        customer: { required: "Please select Client" },
        schema: { required: "Please select Plan" },
        tenure: { required: "Please enter tenure" },
        amount:{
            required : "Please enter invesment amount"
        },
        return_type : {
            required : "Please select role"
        },
        start_date : {
            required : "Please select start date"
        },
        return_percentage : {
            required : "please enter percentage"
        },
        status : {
            required : "Please select status"
        },
        contract : {
            required : "Please select contract"
        },
        contract_reciept: {
            required: "Please select contract reciept",
            accept: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed",
        },
        edit_contract_reciept: {
            required: "Please select contract reciept",
            accept: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed",
        },
        invest_document : {
            required : "Please select investment document",
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"
        },
        edit_invest_document : {
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"
        },
        other_document: { 
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"
        },
        edit_other_document: { 
            extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"
        },
    },
    submitHandler: function (form) {

        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});

$("#resetLink").validate({
    rules: {
        email: { required: true, email: true },
        password: { required: true },
        confirm_password : {
            required: true,
            equalTo: "#password"   
        }
    },
    messages: {
        email: {
            required: "Email is required ",
            email: "Please enter valid email",
        },
        password : {
            required : "Please enter your desired password"
        },
    confirm_password : {
            required : "Please enter your desired password",
        },
    },
    submitHandler: function (form) {

        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});


$("#profileForm").validate({
    rules: {
        fname: { required: true},
        lname: { required: true},
        mobile : {
            required : true,
            number : true,
            minlength : 6,
            maxlength : 10
        },
        country_code: { required:true }
    },
    messages: {
        fname: {
            required: "Please enter first name ",
        },
        lname : {
            required : "Please enter last name"
        },
        mobile : {
            required : "Please enter mobile number"
        },
        country_code : {
            required : "Please select country code"
        }
    },
    submitHandler: function (form) {

        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});
$("#userProfileForm").validate({
    rules: {
        fname: { required: true},
        lname: { required: true},
        dob: { required: true},
        mobile : {
            required : true,
            number : true,
            minlength : 6,
            maxlength : 10
        },
        gender : {required : true}
    },
    messages: {
        fname: {
            required: "Please enter first name ",
        },
        lname: {
            required: "Please enter last name ",
        },
        dob : {
            required : "Please select date of birth"
        },
        mobile : {
            required : "Please enter mobile number"
        },
        gender : {required : 'please select gender'}
    },
    submitHandler: function (form) {

        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});

$("#changePass").validate({
    rules: {
        old_password: { required: true},
        password: { required: true},
        confirm_password :{
            required : true,
            equalTo : '#password'
        }
    },
    messages: {
        old_password: {
            required: "Please enter old password",
        },
        password : {
            required : "Please enter new password"
        },
        confirm_password : {
            required : "Please enter confirm password"
        }
    },
    submitHandler: function (form) {

        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});

$("#Roiform").validate({
    rules: {
        payment_trasfer_reciept : {
            required : true,
            extension: "jpg|png|jpeg|svg|docx|rtf|doc|pdf"
        },
    },
    messages: {
        payment_trasfer_reciept: {
            required: "Please enter file",
            extension : ' extension: "Only type jpg|png|jpeg|svg|docx|rtf|doc|pdf is allowed"'
        },
    },
    submitHandler: function (form) {
        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});

$("#contractCancelForm").validate({
    rules: {
        contractCancelComment : {
            required : true,
        },
    },
    messages: {
        contractCancelComment: {
            required: "Please enter Comment",
        },
    },
    submitHandler: function (form) {
        $('body').attr('disabled','disabled');
        $('#btnSubmit').attr('disabled','disabled');
        $('#btnSubmit').value('please wait');
            $(form).submit();
    }
});
