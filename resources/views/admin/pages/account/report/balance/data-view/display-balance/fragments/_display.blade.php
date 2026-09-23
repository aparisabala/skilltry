<h4> {{ $ledgerCategory['name'] }} </h4>
<div class="row">
    @foreach ($ledgerCategory['items'] as $ledger)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper bg-primary-subtle text-primary rounded-circle me-3">
                        <i class="fa-solid fa-wallet fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{$ledger?->name}}</h6>
                        <h4 class="fw-bold mb-0">৳ {{number_format($ledger?->cash_credit_sum_total_amount - $ledger?->cash_debit_sum_total_amount)}}</h4>
                    </div>
                </div>
            </div>
        </div>  
    @endforeach
</div>