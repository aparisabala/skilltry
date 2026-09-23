$(document).ready(function(){

    if ($('#frmStoreHrEmployment').length > 0) {
        let rules = {
            change_type: { required: true, maxlength: 253 },
            effective_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrEmployment',
            validation: true,
            script: 'admin/hrm/staff/employment',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrEmployment').length > 0) {
        let rules = {
            change_type: { required: true, maxlength: 253 },
            effective_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrEmployment',
            validation: true,
            script: 'admin/hrm/staff/employment/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrEmployment").length > 0) {
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
                data: 'change_type',
                title: table?.change_type
            },
            {
                data: 'effective_date',
                title: table?.effective_date
            },
            {
                data: 'designation_title',
                title: table?.designation_title
            },
            {
                data: 'salary',
                title: table?.salary
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
                    return policy?.hr_employment_crud_edit ? `<a href="${baseurl}admin/hrm/staff/employment/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrEmployment', {
            select: true,
            url: 'admin/hrm/staff/employment/list',
            body: function () {
                return { admin_user_id: $('#admin_user_id').val() };
            },
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrEmployment(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrEmployment",
        script: "admin/hrm/staff/employment/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrEmployment",
        script: "admin/hrm/staff/employment/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrEmploymentPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrEmploymentExcel", dataTable: "yes" })
}
