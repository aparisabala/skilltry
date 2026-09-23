$(document).ready(function(){
    if ($('#frmLoadAccountCashbook').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 255
            }
        };
        PX.ajaxRequest({
            element: 'frmLoadAccountCashbook',
            validation: true,
            script: 'admin/account/report/cashbook/account-cashbook/display',
            rules,
            afterSuccess: {
                type: 'load_html',
                target: 'account-cashbook',
                afterLoad: (req,res) => {
                }
            }
        });
    }
});
