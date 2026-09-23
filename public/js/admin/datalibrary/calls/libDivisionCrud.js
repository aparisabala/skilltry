$(document).ready(function(){

    if ($('#frmStoreLibDivision').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibDivision',
            validation: true,
            script: 'admin/datalibrary/location/division',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibDivision').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibDivision',
            validation: true,
            script: 'admin/datalibrary/location/division/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibDivision").length > 0) {
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
                    if(policy?.lib_division_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/location/division/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibDivision', {
            select: true,
            url: 'admin/datalibrary/location/division/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibDivision(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibDivision",
        script: "admin/datalibrary/location/division/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibDivision",
        script: "admin/datalibrary/location/division/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibDivisionPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibDivisionExcel", dataTable: "yes" })
}
