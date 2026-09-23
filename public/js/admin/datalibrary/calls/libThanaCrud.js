$(document).ready(function(){

    if ($('#frmStoreLibThana').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            lib_division_id: {
                required: true
            },
            lib_district_id: {
                required: true
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibThana',
            validation: true,
            script: 'admin/datalibrary/location/thana',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibThana').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            lib_division_id: {
                required: true
            },
            lib_district_id: {
                required: true
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibThana',
            validation: true,
            script: 'admin/datalibrary/location/thana/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibThana").length > 0) {
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
                data: 'lib_division_id',
                title: table?.division
            },
            {
                data: 'lib_district_id',
                title: table?.district
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
                    if(policy?.lib_thana_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/location/thana/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibThana', {
            select: true,
            url: 'admin/datalibrary/location/thana/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }


    function filterLibThanaDistricts() {
        let division = $('#lib_division_id').val();
        $('#lib_district_id option').each(function(){
            let opt = $(this);
            if (opt.val() === '') { return; }
            if (!division || opt.data('division') == division) {
                opt.show();
            } else {
                opt.hide();
                if (opt.is(':selected')) {
                    $('#lib_district_id').val('');
                }
            }
        });
    }
    if ($('#lib_division_id').length > 0 && $('#lib_district_id').length > 0) {
        filterLibThanaDistricts();
        $('#lib_division_id').on('change', filterLibThanaDistricts);
    }
})

function dtLibThana(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibThana",
        script: "admin/datalibrary/location/thana/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibThana",
        script: "admin/datalibrary/location/thana/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibThanaPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibThanaExcel", dataTable: "yes" })
}
