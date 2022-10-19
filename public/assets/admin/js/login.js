$("#loginForm").validate({
    rules: {
        email: { required: true, email: true },
        password: { required: true },
    },
    messages: {
        email: {
            required: "Please enter email ",
            email: "Please enter valid email",
        },
        password: { required: "Please enter password" },
    },
});

$("#forgetPassword").validate({
    rules: {
        email: { 
            required: true, 
            email: true 
        },
    },
    messages: {
        email: {
            required: "Please enter email ",
            email: "Please enter valid email",
        }
    },
});

