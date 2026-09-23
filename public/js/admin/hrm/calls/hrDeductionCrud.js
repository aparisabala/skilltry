$(document).ready(function(){
    PX?.utils?.dp();

    if ($('#frmStoreHrDeduction').length > 0) {
        let rules = {
            deduction_type: { required: true, maxlength: 253 },
            amount: { required: true },
            deduction_month: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrDeduction',
            validation: true,
            script: 'admin/hrm/staff/deduction',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrDeduction').length > 0) {
        let rules = {
            deduction_type: { required: true, maxlength: 253 },
            amount: { required: true },
            deduction_month: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrDeduction',
            validation: true,
            script: 'admin/hrm/staff/deduction/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrDeduction").length > 0) {
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
                data: 'deduction_type',
                title: table?.deduction_type
            },
            {
                data: 'amount',
                title: table?.amount
            },
            {
                data: 'deduction_month',
                title: table?.deduction_month
            },
            {
                data: 'reason',
                title: table?.reason
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
                    if(policy?.hr_deduction_crud_edit) {
                        str += `<a href="${baseurl}admin/hrm/staff/deduction/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtHrDeduction', {
            select: true,
            url: 'admin/hrm/staff/deduction/list',
            body: function () {
                return { admin_user_id: $('#admin_user_id').val() };
            },
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrDeduction(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrDeduction",
        script: "admin/hrm/staff/deduction/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrDeduction",
        script: "admin/hrm/staff/deduction/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrDeductionPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrDeductionExcel", dataTable: "yes" })
}
