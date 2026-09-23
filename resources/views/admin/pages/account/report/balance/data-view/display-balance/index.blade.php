@extends('admin.layouts.main-layout',["tabTitle" => config('i.service_name')." | ".pxLang($data['lang'],'breadCum.title') ])
@section('page')
    <div class="row">
        <div class="col-md-12">
            @can('display_balance_data_view_view')
                <div class="">
                    @include('admin.pages.account.report.balance.data-view.display-balance.fragments._breadcum')
                    
                    <div class="page-block-body">
                        <div class="card rounded page-block">
                            
                            <div id="defaultPage" class="table-list pages">
                                <div class="mt-2 p-2 p-md-4">
                                    <input type="hidden" id="page-lang" value="{{ json_encode(Lang::get(config('pxcommands.language')[$data['lang']])) }}" />
                                    <div class="container my-5">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="icon-wrapper bg-primary-subtle text-primary rounded-circle me-3">
                                                            <i class="fa-solid fa-wallet fa-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="text-muted mb-1">{{pxLang($data['lang'],'text.cash_in_hand')}}</h6>
                                                            <h4 class="fw-bold mb-0">৳ {{ number_format(($data['cash']['items']?->sum('cash_credit_sum_total_amount') - $data['cash']['items']?->sum('cash_debit_sum_total_amount'))) }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="icon-wrapper bg-success-subtle text-success rounded-circle me-3">
                                                            <i class="fa-solid fa-wallet fa-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="text-muted mb-1">{{pxLang($data['lang'],'text.cash_in_bank')}}</h6>
                                                            <h4 class="fw-bold mb-0">৳  {{ number_format(($data['bank']['items']?->sum('cash_credit_sum_total_amount') - $data['bank']['items']?->sum('cash_debit_sum_total_amount'))) }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @include('admin.pages.account.report.balance.data-view.display-balance.fragments._display',['ledgerCategory'=> $data['cash']])
                                    <div class="mt-3">
                                        @include('admin.pages.account.report.balance.data-view.display-balance.fragments._display',['ledgerCategory'=> $data['bank']])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card rounded page-block">
                    @include('common.view.fragments.-item-403')
                </div>
            @endcan
        </div>
    </div>
@endsection
