$(document).ready(function(){

    if ($('#frmStoreHrLeaveType').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            is_paid: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrLeaveType',
            validation: true,
            script: 'admin/hrm/staff/leave-type',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrLeaveType').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            is_paid: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrLeaveType',
            validation: true,
            script: 'admin/hrm/staff/leave-type/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrLeaveType").length > 0) {
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
                data: 'name',
                title: table?.name
            },
            {
                data: 'is_paid',
                title: table?.is_paid
            },
            {
                data: 'days_per_year',
                title: table?.days_per_year
            },
            {
                data: 'status',
                title: table?.status
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
                    return policy?.hr_leave_type_crud_edit ? `<a href="${baseurl}admin/hrm/staff/leave-type/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrLeaveType', {
            select: true,
            url: 'admin/hrm/staff/leave-type/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrLeaveType(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrLeaveType",
        script: "admin/hrm/staff/leave-type/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrLeaveType",
        script: "admin/hrm/staff/leave-type/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrLeaveTypePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrLeaveTypeExcel", dataTable: "yes" })
}
