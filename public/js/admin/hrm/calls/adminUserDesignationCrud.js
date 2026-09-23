$(document).ready(function(){

    let currentAdminUserId = $('#admin_user_id').val();

    if ($('#frmStoreAdminUserDesignation').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            passing_year: {
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmStoreAdminUserDesignation',
            validation: true,
            script: 'admin/hrm/user/crud/modify/designation',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateAdminUserDesignation').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            passing_year: {
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmUpdateAdminUserDesignation',
            validation: true,
            script: 'admin/hrm/user/crud/modify/designation/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtAdminUserDesignation").length > 0) {
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
                data: 'passing_year',
                title: table?.passing_year
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
                    if(policy?.admin_user_designation_crud_edit) {
                        str += `<a href="${baseurl}admin/hrm/user/crud/modify/designation/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtAdminUserDesignation', {
            select: true,
            url: 'admin/hrm/user/crud/modify/designation/list',
            columns: col_draft,
            body: {admin_user_id: currentAdminUserId},
            pdf: [1, 2]
        });
    }
})

function dtAdminUserDesignation(table, api, op) {
    PX.deleteAll({
        element: "deleteAllAdminUserDesignation",
        script: "admin/hrm/user/crud/modify/designation/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllAdminUserDesignation",
        script: "admin/hrm/user/crud/modify/designation/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadAdminUserDesignationPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadAdminUserDesignationExcel", dataTable: "yes" })
}
