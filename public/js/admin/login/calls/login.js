$(document).ready(function(){
    if($("#frmAdminUserLogin").length > 0) {
        let rules = {
            email: {
                required: true,
                email: true,
            },
            password:{
                required: true,
                minlength: 8,
            },
        };
        PX?.ajaxRequest({
            element: "frmAdminUserLogin",
            validation: true,
            script: "admin/login",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data",
            }
        });
        //vpx_attach
    }
})