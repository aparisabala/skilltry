$(document).ready(function(){

    if ($('#frmStoreLibDistrict').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            lib_division_id: {
                required: true
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibDistrict',
            validation: true,
            script: 'admin/datalibrary/location/district',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibDistrict').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
            lib_division_id: {
                required: true
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibDistrict',
            validation: true,
            script: 'admin/datalibrary/location/district/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibDistrict").length > 0) {
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
                data: 'created_at',
                title: table?.created
            },

            {
                data: null,
                title: table?.action,
                class: 'text-end',
                render: function (data, type, row) {
                    let str = ``;
                    if(policy?.lib_district_edit) {
                        str += `<a href="${baseurl}admin/datalibrary/location/district/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibDistrict', {
            select: true,
            url: 'admin/datalibrary/location/district/list',
            columns: col_draft,
            pdf: [1, 2]
        });
    }

})

function dtLibDistrict(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibDistrict",
        script: "admin/datalibrary/location/district/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibDistrict",
        script: "admin/datalibrary/location/district/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibDistrictPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibDistrictExcel", dataTable: "yes" })
}
