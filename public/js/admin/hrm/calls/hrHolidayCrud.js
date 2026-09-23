$(document).ready(function(){
    PX?.utils?.dp();

    if ($('#frmStoreHrHoliday').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            holiday_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmStoreHrHoliday',
            validation: true,
            script: 'admin/hrm/staff/holiday',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateHrHoliday').length > 0) {
        let rules = {
            name: { required: true, maxlength: 253 },
            holiday_date: { required: true }
        };
        PX.ajaxRequest({
            element: 'frmUpdateHrHoliday',
            validation: true,
            script: 'admin/hrm/staff/holiday/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtHrHoliday").length > 0) {
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
                data: 'holiday_date',
                title: table?.holiday_date
            },
            {
                data: 'note',
                title: table?.note
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
                    return policy?.hr_holiday_crud_edit ? `<a href="${baseurl}admin/hrm/staff/holiday/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>` : '';
                }
            },
        ];
        PX.renderDataTable('dtHrHoliday', {
            select: true,
            url: 'admin/hrm/staff/holiday/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }
})

function dtHrHoliday(table, api, op) {
    PX.deleteAll({
        element: "deleteAllHrHoliday",
        script: "admin/hrm/staff/holiday/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllHrHoliday",
        script: "admin/hrm/staff/holiday/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadHrHolidayPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadHrHolidayExcel", dataTable: "yes" })
}
