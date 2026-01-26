$(document).ready(function(){

    if ($('#frmStoreAdminUserRole').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            code: {
                required: true,
                minlength: 2,
                maxlength: 3
            }
        };
        PX.ajaxRequest({
            element: 'frmStoreAdminUserRole',
            validation: true,
            script: 'admin/system/user/user-role',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateAdminUserRole').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
             code: {
                required: true,
                minlength: 2,
                maxlength: 3
            }
        };
        PX.ajaxRequest({
            element: 'frmUpdateAdminUserRole',
            validation: true,
            script: 'admin/system/user/user-role/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtAdminUserRole").length > 0) {
        const {pageLang={},policy={}} = PX?.config;
        const {table={}} = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },

            {
                data: 'name',
                title: table?.name
            },
             {
                data: 'code',
                title: table?.code
            },

            {
                data: 'created_at',
                title: table?.created
            },

            {
                data: null,
                title: table?.action,
                class: 'text-end',
                render: function (data, type, row) {
                    let str = ``;
                    if(policy?.system_user_roles_edit) {
                        str += `<a href="${baseurl}admin/system/user/user-role/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtAdminUserRole', {
            select: true,
            url: 'admin/system/user/user-role/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtAdminUserRole(table, api, op) {
    PX.deleteAll({
        element: "deleteAllAdminUserRole",
        script: "admin/system/user/user-role/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllAdminUserRole",
        script: "admin/system/user/user-role/update-list",
        confirm: true,
        dataCols: {
            key: "ids",
            items: [
                {
                    index: 1,
                    name: "ids",
                    type: "input",
                    data: [],
                },
                {
                    index: 1,
                    name: "serial",
                    type: "input",
                    data: []
                }
            ]
        },
        api,
        afterSuccess: {
            type: "inflate_response_data"
        }
    });
    PX?.dowloadPdf({ ...op, btn: "downloadAdminUserRolePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadAdminUserRoleExcel", dataTable: "yes" })
}
