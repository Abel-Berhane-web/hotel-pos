@extends('layouts.app')
@section('content')
@push('styles')
<style>
    .pos-grid { display: grid; grid-template-columns: 1fr 380px; gap: 20px; min-height: calc(100vh - 140px); }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; }
    .product-btn { background: #fff; border: 1px solid #e9ecef; border-radius: 10px; padding: 14px 10px; text-align: center; cursor: pointer; transition: all .15s; }
    .product-btn:hover { border-color: #0d6efd; background: #f0f6ff; }
    .product-btn .pname { font-weight: 600; font-size: .85rem; margin-bottom: 4px; }
    .product-btn .pprice { color: #0d6efd; font-weight: 700; font-size: .9rem; }
    .cart-panel { background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; position: sticky; top: 80px; max-height: calc(100vh - 120px); overflow-y: auto; }
    .cart-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f3f5; }
    .cart-item .ci-name { font-weight: 600; font-size: .85rem; }
    .cart-item .ci-price { color: #6c757d; font-size: .8rem; }
    .qty-control { display: flex; align-items: center; gap: 6px; }
    .qty-control button { width: 28px; height: 28px; border-radius: 6px; border: 1px solid #dee2e6; background: #f8f9fa; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .qty-control span { font-weight: 600; min-width: 20px; text-align: center; }
    .category-tabs .nav-link { font-size: .85rem; font-weight: 600; border-radius: 8px !important; padding: 6px 16px; }
    @media(max-width:992px) { .pos-grid { grid-template-columns: 1fr; } }
</style>
@endpush

<div class="pos-grid">
    {{-- Products --}}
    <div>
        <ul class="nav nav-pills category-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" href="#" data-cat="all">{{ __('m.all') }}</a></li>
            @foreach($categories as $cat)
                <li class="nav-item"><a class="nav-link" href="#" data-cat="{{ $cat->id }}">{{ $cat->name }}</a></li>
            @endforeach
        </ul>
        <div class="product-grid" id="productGrid">
            @foreach($products as $product)
            <div class="product-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->selling_price }}" data-cat="{{ $product->category_id }}" onclick="addToCart(this)">
                <div class="pname">{{ $product->name }}</div>
                <div class="pprice">{{ number_format($product->selling_price, 2) }}</div>
                @if($product->track_stock)<small class="text-muted">{{ __('m.stock') }}: {{ $product->stock_quantity }}</small>@endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Cart --}}
    <div class="cart-panel">
        <h5 class="fw-bold mb-3"><i class="bi bi-cart3 me-2"></i>{{ __('m.cart') }}</h5>
        <div id="cartItems" style="flex:1;overflow-y:auto;"></div>
        <div id="emptyCart" class="text-center text-muted py-4"><i class="bi bi-cart-x" style="font-size:2rem;"></i><p class="mt-2">{{ __('m.empty_cart') }}</p></div>

        <hr>
        <div class="d-flex justify-content-between mb-3">
            <span class="fw-bold">{{ __('m.total') }}</span>
            <span class="fw-bold text-primary" id="cartTotal">0.00 {{ __('m.etb') }}</span>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf
            <div id="cartInputs"></div>
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">{{ __('m.employee') }}</label>
                <select name="employee_id" class="form-select">
                    <option value="">{{ __('m.select_employee') }}</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">{{ __('m.payment_method') }}</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['cash', 'bank_transfer', 'telebirr', 'cbe_birr', 'credit'] as $pm)
                    <label class="btn btn-outline-secondary btn-sm {{ $loop->first ? 'active' : '' }}" style="font-size:.8rem;">
                        <input type="radio" name="payment_method" value="{{ $pm }}" {{ $loop->first ? 'checked' : '' }} class="btn-check"> {{ __('m.'.$pm) }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">{{ __('m.note') }}</label>
                <input type="text" name="note" class="form-control form-control-sm" placeholder="{{ __('m.note') }}...">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn" disabled>
                <i class="bi bi-check-circle me-1"></i> {{ __('m.place_order') }}
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
let cart = {};
const etb = '{{ __("m.etb") }}';

function addToCart(el) {
    const id = el.dataset.id, name = el.dataset.name, price = parseFloat(el.dataset.price);
    if (cart[id]) { cart[id].qty++; } else { cart[id] = { name, price, qty: 1 }; }
    renderCart();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function removeItem(id) { delete cart[id]; renderCart(); }

function renderCart() {
    const container = document.getElementById('cartItems');
    const inputs = document.getElementById('cartInputs');
    const empty = document.getElementById('emptyCart');
    const keys = Object.keys(cart);

    if (keys.length === 0) {
        container.innerHTML = '';
        inputs.innerHTML = '';
        empty.style.display = 'block';
        document.getElementById('cartTotal').textContent = '0.00 ' + etb;
        document.getElementById('submitBtn').disabled = true;
        return;
    }

    empty.style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
    let html = '', inputHtml = '', total = 0;

    keys.forEach((id, i) => {
        const item = cart[id];
        const lineTotal = item.price * item.qty;
        total += lineTotal;
        html += `<div class="cart-item">
            <div><div class="ci-name">${item.name}</div><div class="ci-price">${item.price.toFixed(2)} × ${item.qty} = ${lineTotal.toFixed(2)}</div></div>
            <div class="d-flex align-items-center gap-2">
                <div class="qty-control">
                    <button type="button" onclick="changeQty('${id}',-1)">−</button>
                    <span>${item.qty}</span>
                    <button type="button" onclick="changeQty('${id}',1)">+</button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem('${id}')"><i class="bi bi-x"></i></button>
            </div>
        </div>`;
        inputHtml += `<input type="hidden" name="items[${i}][product_id]" value="${id}"><input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
    });

    container.innerHTML = html;
    inputs.innerHTML = inputHtml;
    document.getElementById('cartTotal').textContent = total.toFixed(2) + ' ' + etb;
}

// Category tabs
document.querySelectorAll('.category-tabs .nav-link').forEach(tab => {
    tab.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.category-tabs .nav-link').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const cat = tab.dataset.cat;
        document.querySelectorAll('.product-btn').forEach(p => {
            p.style.display = (cat === 'all' || p.dataset.cat === cat) ? '' : 'none';
        });
    });
});

// Payment radio buttons
document.querySelectorAll('input[name="payment_method"]').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('input[name="payment_method"]').forEach(r2 => r2.closest('label').classList.remove('active'));
        r.closest('label').classList.add('active');
    });
});
</script>
@endpush
@endsection
