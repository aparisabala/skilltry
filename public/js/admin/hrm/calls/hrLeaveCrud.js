$(document).ready(function(){
    PX?.utils?.dp();

    if ($('#frmStoreHrLeave').length > 0) {
        let rules = {
            hr_leave_type_id: { required: true },
            from_date: { required: true },
            to_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrLeave',
            validation: true,
            script: 'admin/hrm/staff/leave',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrLeave').length > 0) {
        let rules = {
            hr_leave_type_id: { required: true },
            from_date: { required: true },
            to_date: { required: true },
            status: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrLeave',
            validation: true,
            script: 'admin/hrm/staff/leave/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrLeave").length > 0) {
        const {pageLang={},policy={}} = PX?.config;
        const {table={}} = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },
            {
                data: null,
                title: table?.serial,
                class: 'text-center',
                width: '200px',
                render: function (data, type, row) {
                    return `<input type="number" value="` + data.serial + `" class="form-control serial"><input type="hidden" value="` + data.id + `" class="form-control ids">`;
                }
            },
            {
                data: 'from_date',
                title: table?.from_date
            },
            {
                data: 'to_date',
                title: table?.to_date
            },
            {
                data: 'reason',
                title: table?.reason
            },
            {
                data: 'status',
                title: table?.status
            },
            {
                data: 'days',
                title: table?.days
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
                    if(policy?.hr_leave_crud_edit) {
                        str += `<a href="${baseurl}admin/hrm/staff/leave/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtHrLeave', {
            select: true,
            url: 'admin/hrm/staff/leave/list',
            body: function () {
                return { admin_user_id: $('#admin_user_id').val() };
            },
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrLeave(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrLeave",
        script: "admin/hrm/staff/leave/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrLeave",
        script: "admin/hrm/staff/leave/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrLeavePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrLeaveExcel", dataTable: "yes" })
}
