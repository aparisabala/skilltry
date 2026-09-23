$(document).ready(function(){

    if ($('#frmStoreLibCategory').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmStoreLibCategory',
            validation: true,
            script: 'admin/category',
            rules,
            afterSuccess: {
                type: 'inflate_reset_response_data',
            }
        });
    }

    if ($('#frmUpdateLibCategory').length > 0) {
        let rules = {
            name: {
                required: true,
                maxlength: 253
            },
        };
        PX.ajaxRequest({
            element: 'frmUpdateLibCategory',
            validation: true,
            script: 'admin/category/'+$("#patch_id").val(),
            rules,
            afterSuccess: {
                type: 'inflate_response_data',
            }
        });
    }

    if ($("#dtLibCategory").length > 0) {
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
                    if(policy?.lib_subcategory_view) {
                        str += `<a href="${baseurl}admin/category/${data.id}/subcategory" class="btn btn-outline-primary btn-sm me-1" title="Manage Subcategories">
                            <i class="fas fa-sitemap"></i>
                        </a>`;
                    }
                    if(policy?.lib_category_edit) {
                        str += `<a href="${baseurl}admin/category/${data.id}/edit" class="btn btn-outline-secondary btn-sm edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>`;
                    }
                    return str;
                }
            },
        ];
        PX.renderDataTable('dtLibCategory', {
            select: true,
            url: 'admin/category/list',
            columns: col_draft,
            pdf: [2, 3]
        });
    }

})

function dtLibCategory(table, api, op) {
    PX.deleteAll({
        element: "deleteAllLibCategory",
        script: "admin/category/delete-list",
        confirm: true,
        api,
    });
    PX.updateAll({
        element: "updateAllLibCategory",
        script: "admin/category/update-list",
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
    PX?.dowloadPdf({ ...op, btn: "downloadLibCategoryPdf", dataTable: "yes" })
    PX?.dowloadExcel({ ...op, btn: "downloadLibCategoryExcel", dataTable: "yes" })
}
