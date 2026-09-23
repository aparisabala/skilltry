$(document).ready(function(){

    if ($('#frmStoreHrPayComponent').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            component_type: { required: true, maxlength: 253 },
            calc_type: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrPayComponent',
            validation: true,
            script: 'admin/hrm/staff/pay-component',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrPayComponent').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            component_type: { required: true, maxlength: 253 },
            calc_type: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrPayComponent',
            validation: true,
            script: 'admin/hrm/staff/pay-component/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrPayComponent").length > 0) {
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
                data: 'component_type',
                title: table?.component_type
            },
            {
                data: 'calc_type',
                title: table?.calc_type
            },
            {
                data: 'default_value',
                title: table?.default_value
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
                    return policy?.hr_pay_component_crud_edit ? `<a href="${baseurl}admin/hrm/staff/pay-component/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrPayComponent', {
            select: true,
            url: 'admin/hrm/staff/pay-component/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrPayComponent(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrPayComponent",
        script: "admin/hrm/staff/pay-component/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrPayComponent",
        script: "admin/hrm/staff/pay-component/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrPayComponentPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrPayComponentExcel", dataTable: "yes" })
}
