$(document).ready(function(){

    if ($('#frmStoreLibDepartment').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibDepartment',
            validation: true,
            script: 'admin/datalibrary/department',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibDepartment').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibDepartment',
            validation: true,
            script: 'admin/datalibrary/department/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibDepartment").length > 0) {
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
                    if(policy?.lib_department_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/department/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibDepartment', {
            select: true,
            url: 'admin/datalibrary/department/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibDepartment(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibDepartment",
        script: "admin/datalibrary/department/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibDepartment",
        script: "admin/datalibrary/department/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibDepartmentPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibDepartmentExcel", dataTable: "yes" })
}
