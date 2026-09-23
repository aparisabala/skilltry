$(document).ready(function(){

    let currentCategoryId = $('#current-category-id').val();
    let currentSubcategoryId = $('#current-subcategory-id').val();

    if ($('#frmStoreLibSpecialization').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibSpecialization',
            validation: true,
            script: 'admin/category/'+currentCategoryId+'/subcategory/'+currentSubcategoryId+'/specialization',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibSpecialization').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibSpecialization',
            validation: true,
            script: 'admin/category/'+currentCategoryId+'/subcategory/'+currentSubcategoryId+'/specialization/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibSpecialization").length > 0) {
        const {pageLang={},policy={}} = PX?.config;
        const {table={}} = pageLang;
        let col_draft = [
            {
                data: 'id',
                title: table?.id
            },

            {
                data: 'image',
                title: table?.image,
                orderable: false,
                searchable: false
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
                    if(policy?.lib_specialization_edit) {
                        str += `<a href="${baseurl}admin/category/${currentCategoryId}/subcategory/${currentSubcategoryId}/specialization/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibSpecialization', {
            select: true,
            url: 'admin/category/'+currentCategoryId+'/subcategory/'+currentSubcategoryId+'/specialization/list',
            columns: col_draft,
            pdf: [2, 3]
        });
    }

})

function dtLibSpecialization(table, api, op) {
    let currentCategoryId = $('#current-category-id').val();
    let currentSubcategoryId = $('#current-subcategory-id').val();
    PX.deleteAll({
        element: "deleteAllLibSpecialization",
        script: "admin/category/"+currentCategoryId+"/subcategory/"+currentSubcategoryId+"/specialization/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibSpecialization",
        script: "admin/category/"+currentCategoryId+"/subcategory/"+currentSubcategoryId+"/specialization/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibSpecializationPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibSpecializationExcel", dataTable: "yes" })
}
