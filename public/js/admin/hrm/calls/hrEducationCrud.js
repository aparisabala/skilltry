$(document).ready(function(){

    if ($('#frmStoreHrEducation').length > 0) {
        let rules = {
            degree: { required: true, maxlength: 253 },
            institute: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrEducation',
            validation: true,
            script: 'admin/hrm/staff/education',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrEducation').length > 0) {
        let rules = {
            degree: { required: true, maxlength: 253 },
            institute: { required: true, maxlength: 253 }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrEducation',
            validation: true,
            script: 'admin/hrm/staff/education/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrEducation").length > 0) {
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
                data: 'degree',
                title: table?.degree
            },
            {
                data: 'institute',
                title: table?.institute
            },
            {
                data: 'board_university',
                title: table?.board_university
            },
            {
                data: 'passing_year',
                title: table?.passing_year
            },
            {
                data: 'result',
                title: table?.result
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
                    return policy?.hr_education_crud_edit ? `<a href="${baseurl}admin/hrm/staff/education/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrEducation', {
            select: true,
            url: 'admin/hrm/staff/education/list',
            body: function () {
                return { admin_user_id: $('#admin_user_id').val() };
            },
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrEducation(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrEducation",
        script: "admin/hrm/staff/education/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrEducation",
        script: "admin/hrm/staff/education/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrEducationPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrEducationExcel", dataTable: "yes" })
}
