<div class="container my-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-3">
            <ul class="list-group list-group-flush small">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-wallet text-primary me-2"></i> {{  pxLang($data['lang'], 'text.from_account')  }}</span>
                    <strong class="text-dark">{{ $data['draft']?->debit?->name}}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-building-columns text-success me-2"></i> {{  pxLang($data['lang'], 'text.to_account')  }}</span>
                    <strong class="text-dark">{{ $data['draft']?->credit?->name}}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-money-bill-wave text-warning me-2"></i>{{  pxLang($data['lang'], 'text.total_amount')  }}</span>
                    <span class="badge bg-warning text-white rounded-pill px-3">
                        ৳ {{ number_format($data['total']) }}
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span> <i class="fa-solid fa-boxes-stacked text-info me-2"></i> {{  pxLang($data['lang'], 'text.total_items')  }}  </span>
                    <span class="badge bg-info rounded-pill px-3">
                        {{ number_format($data['total_items']) }} Items
                    </span>
                </li>
            </ul>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-success w-100 rounded-pill finalSave" data-draft-id="{{ $data['draft']?->id }}">
                    <i class="fa-solid fa-floppy-disk me-1"></i> {{  pxLang($data['lang'], 'btns.final_save')  }}  
                </button>
                {{-- <button class="btn btn-outline-danger w-100 rounded-pill">
                    <i class="fa-solid fa-trash me-1"></i> {{  pxLang($data['lang'], 'btns.delete_draft')  }} 
                </button> --}}
            </div>
        </div>
    </div>
</div>