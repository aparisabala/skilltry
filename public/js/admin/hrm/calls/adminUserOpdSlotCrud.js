$(document).ready(function(){

    let currentAdminUserId = $('#admin_user_id').val();

    if ($('#frmStoreAdminUserOpdSlot').length > 0) {
        let rules = {
            year: {
                required: true
            },
            month: {
                required: true
            },
            day: {
                required: true
            },
            slot: {
                required: true,
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmStoreAdminUserOpdSlot',
            validation: true,
            script: 'admin/hrm/user/crud/modify/doctor-opd-slot',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateAdminUserOpdSlot').length > 0) {
        let rules = {
            year: {
                required: true
            },
            month: {
                required: true
            },
            day: {
                required: true
            },
            slot: {
                required: true,
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmUpdateAdminUserOpdSlot',
            validation: true,
            script: 'admin/hrm/user/crud/modify/doctor-opd-slot/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtAdminUserOpdSlot").length > 0) {
        const {pageLang={},policy={}} = PX?.config;
        const {table={}} = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },

            {
                data: 'year',
                title: table?.year
            },

            {
                data: 'month',
                title: table?.month
            },

            {
                data: 'day',
                title: table?.day
            },

            {
                data: 'slot',
                title: table?.slot
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
                    if(policy?.admin_user_opd_slot_crud_edit) {
                        str += `<a href="${baseurl}admin/hrm/user/crud/modify/doctor-opd-slot/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtAdminUserOpdSlot', {
            select: true,
            url: 'admin/hrm/user/crud/modify/doctor-opd-slot/list',
            columns: col_draft,
            body: {admin_user_id: currentAdminUserId},
            pdf: [1, 2, 3, 4]
        });
    }
})

function dtAdminUserOpdSlot(table, api, op) {
    PX.deleteAll({
        element: "deleteAllAdminUserOpdSlot",
        script: "admin/hrm/user/crud/modify/doctor-opd-slot/delete-list",
        confirm: true,
        api,
    });
    PX?.dowloadPdf({ ...op, btn: "downloadAdminUserOpdSlotPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadAdminUserOpdSlotExcel", dataTable: "yes" })
}
