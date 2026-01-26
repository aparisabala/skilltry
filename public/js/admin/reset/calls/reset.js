$(document).ready(function(){

    if($("#frmSendAdminUserCode").length > 0) {
        let rules = {
            email: {
                required: true,
                email: true
            }
        };

        PX?.ajaxRequest({
            element: "frmSendAdminUserCode",
            validation: true,
            script: "admin/reset/send-code",
            rules,
            afterSuccess: {
                type: "load_html",
                reload: false,
                target: "resetBase",
                afterLoad: (response) => {
                    // const remainingAttempts = response?.response?.data?.extraData?.remainingAttempts;
                    // if (remainingAttempts !== undefined) {
                    //     $("#remainingAttempts").text(remainingAttempts);
                    // } else {
                    //     console.error('remainingAttempts is undefined, response:', response);
                    // }
                    VerifyCode();
                }
            }
        });

        function VerifyCode() {
            if($("#frmVerifyAdminUserCode").length > 0) {
                let verifyrules  = {
                    code: {
                        required: true,
                        number: true,
                    }
                };
                PX?.ajaxRequest({
                    element: "frmVerifyAdminUserCode",
                    validation: true,
                    script: "admin/reset/verify-code",
                    verifyrules,
                    afterSuccess: {
                        type: "load_html",
                        target: "resetBase",
                        afterLoad: (response) => {
                            SetNewPassword();
                        },
                        reload: false
                    },
                });
            }
        }

        function SetNewPassword() {
            if($("#frmChangeAdminUserPassword").length > 0) {
                let chnagerules = {
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        same: '#password'
                    }
                };
                PX?.ajaxRequest({
                    element: "frmChangeAdminUserPassword",
                    validation: true,
                    script: "admin/reset/change-pass",
                    chnagerules,
                    afterSuccess: {
                        type: "inflate_redirect_response_data",
                    }
                });
            }
        }
    }
});
