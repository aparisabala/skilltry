$(document).ready(function(){

    if ($('#frmStoreLibDegree').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibDegree',
            validation: true,
            script: 'admin/datalibrary/degree',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibDegree').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibDegree',
            validation: true,
            script: 'admin/datalibrary/degree/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibDegree").length > 0) {
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
                    if(policy?.lib_degree_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/degree/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibDegree', {
            select: true,
            url: 'admin/datalibrary/degree/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibDegree(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibDegree",
        script: "admin/datalibrary/degree/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibDegree",
        script: "admin/datalibrary/degree/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibDegreePdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibDegreeExcel", dataTable: "yes" })
}
