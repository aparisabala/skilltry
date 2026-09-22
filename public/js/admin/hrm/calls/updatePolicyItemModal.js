$(document).ready(function(){

});
function updatePolicyItem(op){
    console.log("modal loaded");
    if ($('#frmUpdateAdminUserPermission').length > 0) {
        let rules = {
        };
        PX?.ajaxRequest({
            element: 'frmUpdateAdminUserPermission',
            validation: true,
            script: 'admin/hrm/user/user-policy',
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }
    //vpx_attach
}
