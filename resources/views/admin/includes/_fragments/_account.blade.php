@canany(['ac_ledger_crud_view','ac_draft_balance_sheet_crud_view','display_balance_data_view_view','account_cashbook_load_view_view','ac_report_view'])
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
        @can('ac_draft_balance_sheet_crud_view')
        <li class="{{ menuActive('admin/account/transaction/*') }}">
            <a href="javascript: void(0);" class="has-arrow waves-effect">{{pxLang('admin.main-nav','account.menu.trasanction')}}</a>
            <ul class="sub-menu" aria-expanded="true">
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">{{pxLang('admin.main-nav','account.menu.trasanction.receive')}}</a>
                    <ul class="sub-menu ms-3" aria-expanded="true">
                        <li class="{{ menuActive('admin/account/transaction/cash/income') }}">
                            <a href="{{url("admin/account/transaction/cash/income")}}">{{pxLang('admin.main-nav','account.menu.trasanction.receive.cash')}}</a>
                        </li>
                        <li class="{{ menuActive('admin/account/transaction/bank/income') }}">
                            <a href="{{url("admin/account/transaction/bank/income")}}">{{pxLang('admin.main-nav','account.menu.trasanction.receive.bank')}}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">{{pxLang('admin.main-nav','account.menu.trasanction.payment')}}</a>
                    <ul class="sub-menu ms-3" aria-expanded="true">
                        <li class="{{ menuActive('admin/account/transaction/cash/expense') }}">
                            <a href="{{url("admin/account/transaction/cash/expense")}}">{{pxLang('admin.main-nav','account.menu.trasanction.payment.cash')}}</a>
                        </li>
                        <li class="{{ menuActive('admin/account/transaction/bank/expense') }}">
                            <a href="{{url("admin/account/transaction/bank/expense")}}">{{pxLang('admin.main-nav','account.menu.trasanction.payment.bank')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ menuActive('admin/account/transaction/bank/deposit') }}">
                    <a href="{{url("admin/account/transaction/bank/deposit")}}">{{pxLang('admin.main-nav','account.menu.trasanction.bank_deposit')}}</a>
                </li>
                <li class="{{ menuActive('admin/account/transaction/bank/widraw') }}">
                    <a href="{{url("admin/account/transaction/bank/widraw")}}">{{pxLang('admin.main-nav','account.menu.trasanction.bank_widraw')}}</a>
                </li>
            </ul>
        </li>
        @endcan
        @canany(['display_balance_data_view_view','account_cashbook_load_view_view','ac_report_view'])
        @php $acCatalogue = collect(\App\Support\Account\AccountReports::catalogue(fn($p) => Gate::forUser(auth('admin')->user())->allows($p)))->groupBy('group'); @endphp
        <li class="{{ menuActive('admin/account/report/*','admin/account/reports','admin/account/reports/*') }}">
            <a href="javascript: void(0);" class="has-arrow waves-effect">{{pxLang('admin.main-nav','account.menu.reports')}}</a>
            <ul class="sub-menu" aria-expanded="true">
                @can('display_balance_data_view_view')
                <li class="{{ menuActive('admin/account/report/balance/*') }}">
                    <a href="{{ url('admin/account/report/balance/display-balance/display') }}">{{pxLang('admin.main-nav','account.menu.reports.balance')}}</a>
                </li>
                @endcan
                @can('account_cashbook_load_view_view')
                <li class="{{ menuActive('admin/account/report/cashbook/*') }}">
                    <a href="{{ url('admin/account/report/cashbook/account-cashbook') }}">{{pxLang('admin.main-nav','account.menu.reports.cashbook')}}</a>
                </li>
                @endcan
                @if($acCatalogue->isNotEmpty())
                <li class="{{ menuActive('admin/account/reports') }}"><a href="{{ url('admin/account/reports') }}">All account reports</a></li>
                @foreach($acCatalogue as $group => $reports)
                    <li class="{{ menuActive(...collect($reports)->map(fn($r) => 'admin/account/reports/'.$r['key'])->all()) }}">
                        <a href="#" class="has-arrow waves-effect">{{ $group }}</a>
                        <ul class="sub-menu" aria-expanded="true">
                            @foreach($reports as $r)
                                <li class="{{ menuActive('admin/account/reports/'.$r['key']) }}"><a href="{{ url('admin/account/reports/'.$r['key']) }}">{{ $r['title'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                @endif
            </ul>
        </li>
        @endcanany
    </ul>
</li>
@endcanany
