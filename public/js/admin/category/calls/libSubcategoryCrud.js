$(document).ready(function(){

    let currentCategoryId = $('#current-category-id').val();

    if ($('#frmStoreLibSubcategory').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibSubcategory',
            validation: true,
            script: 'admin/category/'+currentCategoryId+'/subcategory',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibSubcategory').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibSubcategory',
            validation: true,
            script: 'admin/category/'+currentCategoryId+'/subcategory/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibSubcategory").length > 0) {
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
                    if(policy?.lib_specialization_view) {
                        str += `<a href="${baseurl}admin/category/${currentCategoryId}/subcategory/${data.id}/specialization" class="btn btn-outline-primary btn-sm me-1" title="Manage Specializations">
                            <i class="fas fa-sitemap"></i>
                        </a>`;
                    }
                    if(policy?.lib_subcategory_edit) {
                        str += `<a href="${baseurl}admin/category/${currentCategoryId}/subcategory/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibSubcategory', {
            select: true,
            url: 'admin/category/'+currentCategoryId+'/subcategory/list',
            columns: col_draft,
            pdf: [2, 3]
        });
    }

})

function dtLibSubcategory(table, api, op) {
    let currentCategoryId = $('#current-category-id').val();
    PX.deleteAll({
        element: "deleteAllLibSubcategory",
        script: "admin/category/"+currentCategoryId+"/subcategory/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibSubcategory",
        script: "admin/category/"+currentCategoryId+"/subcategory/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibSubcategoryPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibSubcategoryExcel", dataTable: "yes" })
}
