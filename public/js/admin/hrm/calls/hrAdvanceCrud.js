$(document).ready(function(){
    PX?.utils?.dp();

    if ($('#frmStoreHrAdvance').length > 0) {
        let rules = {
            amount: { required: true },
            advance_date: { required: true },
            monthly_deduction: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrAdvance',
            validation: true,
            script: 'admin/hrm/staff/advance',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrAdvance').length > 0) {
        let rules = {
            amount: { required: true },
            advance_date: { required: true },
            monthly_deduction: { required: true },
            status: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrAdvance',
            validation: true,
            script: 'admin/hrm/staff/advance/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrAdvance").length > 0) {
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
                data: 'amount',
                title: table?.amount
            },
            {
                data: 'advance_date',
                title: table?.advance_date
            },
            {
                data: 'monthly_deduction',
                title: table?.monthly_deduction
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
                data: 'balance',
                title: table?.balance
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
                    if(policy?.hr_advance_crud_edit) {
                        str += `<a href="${baseurl}admin/hrm/staff/advance/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtHrAdvance', {
            select: true,
            url: 'admin/hrm/staff/advance/list',
            body: function () {
                return { admin_user_id: $('#admin_user_id').val() };
            },
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrAdvance(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrAdvance",
        script: "admin/hrm/staff/advance/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrAdvance",
        script: "admin/hrm/staff/advance/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrAdvancePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrAdvanceExcel", dataTable: "yes" })
}
