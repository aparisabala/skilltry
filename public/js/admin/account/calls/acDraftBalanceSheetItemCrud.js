$(document).ready(function () {

    if ($('#frmStoreAcDraftBalanceSheetItem').length > 0) {
        let rules = {
            ac_draft_transaction_id: {
                required: true,
                maxlength: 253
            },
            folio_number: {
                required: true,
                maxlength: 253
            },
            description: {
                required: true,
                maxlength: 253
            },
            amount: {
                required: true,
                number: true,
                min: 1
            }
        };
        PX.ajaxRequest({
            element: 'frmStoreAcDraftBalanceSheetItem',
            validation: true,
            script: 'admin/account/transaction/crud/manage-item',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateAcDraftBalanceSheetItem').length > 0) {
        let rules = {
            folio_number: {
                required: true,
                maxlength: 253
            },
            description: {
                required: true,
                maxlength: 253
            },
            amount: {
                required: true,
                number: true,
                min: 1
            }
        };
        PX.ajaxRequest({
            element: 'frmUpdateAcDraftBalanceSheetItem',
            validation: true,
            script: 'admin/account/transaction/crud/manage-item/' + $("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtAcDraftBalanceSheetItem").length > 0) {
        const { pageLang = {} } = PX?.config;
        const { table = {} } = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },
            {
                data: 'draft.tran_date',
                title: table?.tran_date
            },
            {
                data: 'folio_number',
                title: table?.folio_number
            },
            {
                data: 'description',
                title: table?.description
            },
            {
                data: 'amount',
                title: table?.amount
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
                    return hmsCan('ac_draft_balance_sheet_item_crud_edit') ? `<a href="${baseurl}admin/account/transaction/crud/manage-item/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtAcDraftBalanceSheetItem', {
            select: true,
            url: 'admin/account/transaction/crud/manage-item/list',
            columns: col_draft,
            body: { ac_draft_transaction_id: $("#ac_draft_transaction_id").val() },
            pdf: [1, 2]
        });
    }

    if ($(".finalSave").length > 0) {
        $(".finalSave").on("click", function () {
            let ac_draft_transaction_id = $(this).attr('data-draft-id');
            PX?.ajaxRequest({
                element: 'finalSave',
                dataType: 'json',
                body: { ac_draft_transaction_id },
                type: 'request',
                confirm: true,
                script: 'admin/account/transaction/crud/manage-item/save',
                afterSuccess: {
                    type: 'inflate_redirect_response_data',
                }
            });
        })
    }
})

function dtAcDraftBalanceSheetItem(table, api, op) {
    PX.deleteAll({
        element: "deleteAllAcDraftBalanceSheetItem",
        script: "admin/account/transaction/crud/manage-item/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllAcDraftBalanceSheetItem",
        script: "admin/account/transaction/crud/manage-item/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadAcDraftBalanceSheetItemPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadAcDraftBalanceSheetItemExcel", dataTable: "yes" })
}
