$(document).ready(function () {

    PX?.utils?.dp({ element: 'tran_date', format: 'Y-m-d' });
    if ($('#frmStoreAcDraftBalanceSheet').length > 0) {
        let rules = {
            tran_date: {
                required: true,
                maxlength: 253
            },
            debit_to: {
                required: true,
                maxlength: 253
            },
            credit_to: {
                required: true,
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmStoreAcDraftBalanceSheet',
            validation: true,
            script: 'admin/account/transaction',
            rules,
            afterSuccess: {
                type: 'inflate_redirect_response_data',
            }
        });
    }

    if ($('#frmUpdateAcDraftBalanceSheet').length > 0) {
        let rules = {
            tran_date: {
                required: true,
                maxlength: 253
            },
            debit_to: {
                required: true,
                maxlength: 253
            },
            credit_to: {
                required: true,
                maxlength: 253
            }
        };
        PX.ajaxRequest({
            element: 'frmUpdateAcDraftBalanceSheet',
            validation: true,
            script: 'admin/account/transaction/' + $("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtAcDraftBalanceSheet").length > 0) {
        const { pageLang = {} } = PX?.config;
        const { table = {} } = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },
            {
                data: 'tran_date',
                title: table?.tran_date
            },
            {
                data: 'tran_type',
                title: table?.tran_type
            },
            {
                data: 'tran_method',
                title: table?.tran_method
            },
            {
                data: 'credit.name',
                title: table?.credit_to
            },
            {
                data: 'debit.name',
                title: table?.debit_to
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
                    return `<a href="${baseurl}admin/account/transaction/crud/manage-item/${data.id}" class="btn btn-outline-info btn-sm edit" title="Add Items">
                        Add Items
                    </a>
                    <a href="${baseurl}admin/account/transaction/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>`;
                }
            },
        ];
        PX.renderDataTable('dtAcDraftBalanceSheet', {
            select: true,
            url: 'admin/account/transaction/list',
            columns: col_draft,
            body: { tran_type: $("#tran_type").val(), tran_method: $("#tran_method").val() },
            pdf: [1, 2]
        });
    }
})

function dtAcDraftBalanceSheet(table, api, op) {
    PX.deleteAll({
        element: "deleteAllAcDraftBalanceSheet",
        script: "admin/account/transaction/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllAcDraftBalanceSheet",
        script: "admin/account/transaction/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadAcDraftBalanceSheetPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadAcDraftBalanceSheetExcel", dataTable: "yes" })
}
