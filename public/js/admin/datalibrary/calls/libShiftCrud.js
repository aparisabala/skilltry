$(document).ready(function(){

    if ($('#frmStoreLibShift').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            start_time: {
                required: true,
                maxlength: 10
            },
            end_time: {
                required: true,
                maxlength: 10
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibShift',
            validation: true,
            script: 'admin/datalibrary/shift',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibShift').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            start_time: {
                required: true,
                maxlength: 10
            },
            end_time: {
                required: true,
                maxlength: 10
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibShift',
            validation: true,
            script: 'admin/datalibrary/shift/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibShift").length > 0) {
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
                data: 'start_time',
                title: table?.start_time
            },

            {
                data: 'end_time',
                title: table?.end_time
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
                    if(policy?.lib_shift_crud_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/shift/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibShift', {
            select: true,
            url: 'admin/datalibrary/shift/list',
            columns: col_draft,
            pdf: [1, 2, 3]
        });
    }

})

function dtLibShift(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibShift",
        script: "admin/datalibrary/shift/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibShift",
        script: "admin/datalibrary/shift/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibShiftPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibShiftExcel", dataTable: "yes" })
}
