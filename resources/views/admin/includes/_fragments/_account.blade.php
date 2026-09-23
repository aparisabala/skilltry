@canany(['ac_ledger_crud_view'])
<li class="{{ menuActive('admin/account/*') }}">
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="fa-solid fa-receipt"></i>
        <span>{{pxLang('admin.main-nav','account.menu')}}</span>
    </a>
    <ul class="sub-menu" aria-expanded="true">
        @can('ac_ledger_crud_view')
        <li class="{{ menuActive('admin/account/ledger/*') }}">
            <a href="javascript: void(0);" class="has-arrow waves-effect">{{pxLang('admin.main-nav','account.menu.ledgers')}}</a>
            <ul class="sub-menu" aria-expanded="true">
                <li class="{{ menuActive('admin/account/ledger/asset') }}">
                    <a href="{{url('admin/account/ledger/asset')}}">{{pxLang('admin.main-nav','account.menu.ledgers.asset')}}</a>
                </li>
                <li class="{{ menuActive('admin/account/ledger/cash') }}">
                    <a href="{{url('admin/account/ledger/cash')}}">{{pxLang('admin.main-nav','account.menu.ledgers.cash')}}</a>
                </li>
                <li class="{{ menuActive('admin/account/ledger/bank') }}">
                    <a href="{{url('admin/account/ledger/bank')}}">{{pxLang('admin.main-nav','account.menu.ledgers.bank')}}</a>
                </li>
                <li class="{{ menuActive('admin/account/ledger/income') }}">
                    <a href="{{url('admin/account/ledger/income')}}">{{pxLang('admin.main-nav','account.menu.ledgers.income')}}</a>
                </li>
                <li class="{{ menuActive('admin/account/ledger/expense') }}">
                    <a href="{{url('admin/account/ledger/expense')}}">{{pxLang('admin.main-nav','account.menu.ledgers.expense')}}</a>
                </li>
            </ul>
        </li>
        @endcan
    </ul>
</li>
@endcanany
