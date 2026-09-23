$(document).ready(function(){

    if ($('#frmStoreLibBank').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibBank',
            validation: true,
            script: 'admin/datalibrary/bank',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibBank').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibBank',
            validation: true,
            script: 'admin/datalibrary/bank/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibBank").length > 0) {
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
                    if(policy?.lib_bank_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/bank/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibBank', {
            select: true,
            url: 'admin/datalibrary/bank/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibBank(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibBank",
        script: "admin/datalibrary/bank/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibBank",
        script: "admin/datalibrary/bank/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibBankPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibBankExcel", dataTable: "yes" })
}
