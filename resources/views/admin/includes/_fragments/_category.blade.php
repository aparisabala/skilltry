@canany(['lib_category_view'])
<li class="{{ menuActive('admin/category', 'admin/category/*') }}">
    <a href="{{url('admin/category')}}" class="waves-effect">
        <i class="bx bx-category" aria-hidden="true"></i>
        <span>{{pxLang('admin.main-nav','category.menu')}}</span>
    </a>
</li>
@endcanany
