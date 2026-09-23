$(document).ready(function(){

    if ($('#frmStoreHrPayGrade').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            min_basic: { required: true },
            max_basic: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrPayGrade',
            validation: true,
            script: 'admin/hrm/staff/pay-grade',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrPayGrade').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            min_basic: { required: true },
            max_basic: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrPayGrade',
            validation: true,
            script: 'admin/hrm/staff/pay-grade/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrPayGrade").length > 0) {
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
                data: 'min_basic',
                title: table?.min_basic
            },
            {
                data: 'max_basic',
                title: table?.max_basic
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
                    return policy?.hr_pay_grade_crud_edit ? `<a href="${baseurl}admin/hrm/staff/pay-grade/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrPayGrade', {
            select: true,
            url: 'admin/hrm/staff/pay-grade/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrPayGrade(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrPayGrade",
        script: "admin/hrm/staff/pay-grade/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrPayGrade",
        script: "admin/hrm/staff/pay-grade/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrPayGradePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrPayGradeExcel", dataTable: "yes" })
}
