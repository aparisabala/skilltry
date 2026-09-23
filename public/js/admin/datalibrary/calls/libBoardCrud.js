$(document).ready(function(){

    if ($('#frmStoreLibBoard').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibBoard',
            validation: true,
            script: 'admin/datalibrary/board',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibBoard').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibBoard',
            validation: true,
            script: 'admin/datalibrary/board/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibBoard").length > 0) {
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
                    if(policy?.lib_board_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/board/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibBoard', {
            select: true,
            url: 'admin/datalibrary/board/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibBoard(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibBoard",
        script: "admin/datalibrary/board/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibBoard",
        script: "admin/datalibrary/board/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibBoardPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibBoardExcel", dataTable: "yes" })
}
