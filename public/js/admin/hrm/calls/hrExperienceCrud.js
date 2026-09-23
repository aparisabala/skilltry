$(document).ready(function(){

    if ($('#frmStoreHrExperience').length > 0) {
        let rules = {
            organization: { required: true, maxlength: 253 },
            position: { required: true, maxlength: 253 },
            from_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrExperience',
            validation: true,
            script: 'admin/hrm/staff/experience',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrExperience').length > 0) {
        let rules = {
            organization: { required: true, maxlength: 253 },
            position: { required: true, maxlength: 253 },
            from_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrExperience',
            validation: true,
            script: 'admin/hrm/staff/experience/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrExperience").length > 0) {
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
                data: 'organization',
                title: table?.organization
            },
            {
                data: 'position',
                title: table?.position
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
                data: 'last_salary',
                title: table?.last_salary
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
                    return policy?.hr_experience_crud_edit ? `<a href="${baseurl}admin/hrm/staff/experience/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrExperience', {
            select: true,
            url: 'admin/hrm/staff/experience/list',
            body: function () {
                return { admin_user_id: $('#admin_user_id').val() };
            },
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrExperience(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrExperience",
        script: "admin/hrm/staff/experience/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrExperience",
        script: "admin/hrm/staff/experience/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrExperiencePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrExperienceExcel", dataTable: "yes" })
}
