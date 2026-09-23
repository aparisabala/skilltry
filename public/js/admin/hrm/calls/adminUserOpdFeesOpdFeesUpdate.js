$(document).ready(function(){

    if ($('#frmAdminUserOpdFeesOpdFeesUpdate').length > 0) {
        let rules = {
            doctor_fees: {
                required: true,
                number: true,
                min: 0
            },
            hospital_fees: {
                required: true,
                number: true,
                min: 0
            },
            service_fees: {
                required: true,
                number: true,
                min: 0
            },
            ipd_fees: {
                number: true,
                min: 0
            }
        };
        PX.ajaxRequest({
            element: 'frmAdminUserOpdFeesOpdFeesUpdate',
            validation: true,
            script: 'admin/hrm/user/crud/modify/opd-fees/update',
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

})
