$(document).ready(function(){
    if($("#frmUpdateAdminUserSetupProfile").length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            mobile_number: {
                required: true,
            },
            email: {
                required: true,
                maxlength: 253,
                email: true,
            },
            new_password: {
                required: true,
                minlength: 8
            },
            confim_password: {
                required: true,
                minlength: 8
            },
        };
        if($("#img_uploaded").val() == "no") {
            rules.image = {
                required: true,
            }
        }
        PX?.ajaxRequest({
            element: "frmUpdateAdminUserSetupProfile",
            validation: true,
            script: "admin/setup/profile",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data"
            }
        });
    }

     if($("#frmAdminUserUpdateProfile").length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            mobile_number: {
                required: true,
            },
            email: {
                required: true,
                maxlength: 253,
                email: true,
            }
        };
        if($("#img_uploaded").val() == "no") {
            rules.image = {
                required: true,
            }
        }
        PX?.ajaxRequest({
            element: "frmAdminUserUpdateProfile",
            validation: true,
            script: "admin/setup/profile-update",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data"
            }
        });
    }

    if($("#frmUpdateAdminUserPasssword").length > 0) {
        let rules = {
            old_password: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            confirm_password: {
                required: true,
                minlength: 8,
            },
        };
        PX?.ajaxRequest({
            element: "frmUpdateAdminUserPasssword",
            validation: true,
            script: "admin/setup/password-update",
            rules,
            afterSuccess: {
                type: "inflate_redirect_response_data"
            }
        });

    }

    //vpx_attach
})
