$(document).ready(function(){

    if ($('#frmStoreLibSkill').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibSkill',
            validation: true,
            script: 'admin/datalibrary/skill',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibSkill').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibSkill',
            validation: true,
            script: 'admin/datalibrary/skill/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibSkill").length > 0) {
        const {pageLang={},policy={}} = PX?.config;
        const {table={}} = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },

            {
                data: 'name',
                title: table?.name
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
                    if(policy?.lib_skill_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/skill/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibSkill', {
            select: true,
            url: 'admin/datalibrary/skill/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibSkill(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibSkill",
        script: "admin/datalibrary/skill/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibSkill",
        script: "admin/datalibrary/skill/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibSkillPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibSkillExcel", dataTable: "yes" })
}
