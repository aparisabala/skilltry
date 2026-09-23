<div class="tool-box d-flex flex-row justify-content-end align-items-center bread-cum">
    @can('lib_degree_store')
        <button data-prop='{"page": "addPage","server": "no"}' class="btn btn-primary btn-soft-primary waves-effect waves-light d-flex justify-items-center align-items-center viewAction fw-bold me-2">
            <span class="bx bx-plus fw-bold"></span> <span class="d-none d-md-block ms-1"> {{pxLang($data['lang'],'','common.btns.crud_add')}} </span>
        </button>
    @endcan
    @if(count($data['items']) > 0)
        @can('lib_degree_bulk_update')
        @endcan
        @can('lib_degree_delete')
            <button class="btn btn-danger btn-soft-danger waves-effect waves-light waves-light d-flex justify-items-center align-items-center  fw-bold me-2 deleteAllLibDegree"><span class="bx bx-trash"></span> <span class="d-none d-md-block ms-1">  {{pxLang($data['lang'],'','common.btns.crud_delete')}}   </span> </button>
        @endcan
        @can('lib_degree_pdf')
            <button class="btn btn-info btn-soft-info waves-effect waves-light d-flex justify-items-center align-items-center  fw-bold  me-2" id="downloadLibDegreePdf"  data-pdf-op='{"file_name":"{{ config('i.service_domain').'_libdegree_list'  }}"}'><span class="bx bxs-file-pdf"></span> <span class="d-none d-md-block ms-1"> {{pxLang($data['lang'],'','common.btns.crud_pdf')}}  </span> </button>
        @endcan
        @can('lib_degree_excel')
            <button class="btn btn-warning btn-soft-warning waves-effect waves-light d-flex justify-items-center align-items-center  fw-bold" id="downloadLibDegreeExcel"  data-excel-op='{"file_name":"{{ config('i.service_domain').'_libdegree_list'  }}"}'><span class="bx bxs-file-export"></span> <span class="d-none d-md-block ms-1"> {{pxLang($data['lang'],'','common.btns.crud_excel')}}  </span> </button>
        @endcan
    @endif
</div>
