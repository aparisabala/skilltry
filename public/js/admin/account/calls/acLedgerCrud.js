$(document).ready(function(){

    if ($('#frmStoreAcLedger').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmStoreAcLedger',
            validation: true,
            script: 'admin/account/ledger',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateAcLedger').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmUpdateAcLedger',
            validation: true,
            script: 'admin/account/ledger/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtAcLedger").length > 0) {
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
                data: 'note',
                title: table?.note
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
                    return policy?.ac_ledger_crud_edit ? `<a href="${baseurl}admin/account/ledger/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtAcLedger', {
            select: true,
            url: 'admin/account/ledger/list',
            columns: col_draft,
            body: {ledger_type: $("#ledger_type").val()},
            pdf: [1, 2]
        });
    }
})

function dtAcLedger(table, api, op) {
    PX.deleteAll({
        element: "deleteAllAcLedger",
        script: "admin/account/ledger/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllAcLedger",
        script: "admin/account/ledger/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadAcLedgerPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadAcLedgerExcel", dataTable: "yes" })
}
