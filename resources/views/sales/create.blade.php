@extends('layouts.app')

@section('title', 'Vender')

@section('content')
<div class="page-head">
    <div>
        <h1>Punto de venta</h1>
        <div class="sub">Toca los productos para agregarlos y confirma con el botón rosa</div>
    </div>
</div>

<div class="pos">
    <div>
        <form method="GET" action="{{ route('sales.create') }}" class="pos-search flex gap">
            <input type="text" name="q" value="{{ $search }}" placeholder="Buscar producto…"
                   style="flex:1;padding:11px 15px;border:2px solid var(--border);border-radius:var(--radius-pill);font-family:inherit;font-size:14px;">
            <button class="btn btn-outline" type="submit"><x-icon n="search" /> Buscar</button>
        </form>

        @if ($products->isEmpty())
            <div class="card empty-state"><x-icon n="box" />No hay productos con existencia disponible.</div>
        @else
            <div class="pos-grid">
                @foreach ($products as $product)
                    <button type="button" class="item-card"
                            onclick='addItem(@json($product->id), @json($product->name), {{ $product->price }}, {{ $product->stock }})'>
                        <img class="photo" src="{{ $product->photo_url }}" alt="">
                        <div class="body">
                            <div class="name">{{ $product->name }}</div>
                            <div class="meta">
                                <span class="price">${{ number_format($product->price, 2) }}</span>
                                <span class="stock">{{ $product->stock }} pzas</span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="basket">
        <h3><x-icon n="cart" /> Tu venta</h3>
        <div class="basket-items" id="basket-items">
            <div class="basket-empty">Elige un producto para empezar.</div>
        </div>
        <div class="basket-foot">
            <div class="line"><span>Subtotal</span><span id="f-subtotal">$0.00</span></div>
            <div class="line"><span>IVA (16%)</span><span id="f-tax">$0.00</span></div>
            <div class="total"><span>Total</span><span id="f-total">$0.00</span></div>
            <button class="btn btn-pink btn-block btn-lg" id="btn-checkout" onclick="openModal()" disabled>
                Confirmar venta
            </button>
        </div>
    </div>
</div>

<div class="modal-bg" id="modal">
    <div class="modal-box">
        <div class="modal-head">
            <div class="badge"><x-icon n="check" style="width:20px;height:20px;" /></div>
            <div>
                <h3>Confirmar venta</h3>
                <p>Revisa los datos antes de guardar</p>
            </div>
        </div>
        <form method="POST" action="{{ route('sales.store') }}" id="sale-form">
            @csrf
            <div class="modal-body">
                <div class="form-grid" style="margin-bottom:16px;">
                    <div class="field full">
                        <label>Cliente (opcional)</label>
                        <input type="text" name="customer_name" placeholder="Nombre del cliente">
                    </div>
                    <div class="field full">
                        <label>Correo del cliente (opcional, recibe su ticket)</label>
                        <input type="email" name="customer_email" placeholder="cliente@correo.com">
                    </div>
                    <div class="field full">
                        <label>Forma de pago</label>
                        <select name="payment_method">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>

                <div class="modal-summary">
                    <div class="line"><span id="m-count">0 artículos</span><span id="m-subtotal">$0.00</span></div>
                    <div class="line"><span>IVA (16%)</span><span id="m-tax">$0.00</span></div>
                    <div class="line total"><span>Total a pagar</span><span id="m-total">$0.00</span></div>
                </div>
                <div id="hidden-items"></div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn btn-pink">Sí, cobrar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const TAX_RATE = 0.16;
    let basket = {};

    const fmt = n => '$' + n.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const escapeHtml = t => String(t).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]);

    function addItem(id, name, price, stock) {
        if (basket[id]) {
            if (basket[id].quantity < stock) basket[id].quantity++;
        } else {
            basket[id] = {id, name, price, stock, quantity: 1};
        }
        draw();
    }

    function changeQty(id, delta) {
        if (!basket[id]) return;
        basket[id].quantity += delta;
        if (basket[id].quantity <= 0) delete basket[id];
        else if (basket[id].quantity > basket[id].stock) basket[id].quantity = basket[id].stock;
        draw();
    }

    function totals() {
        let subtotal = 0, count = 0;
        Object.values(basket).forEach(i => { subtotal += i.price * i.quantity; count += i.quantity; });
        const tax = Math.round(subtotal * TAX_RATE * 100) / 100;
        return {subtotal, tax, total: subtotal + tax, count};
    }

    function draw() {
        const box = document.getElementById('basket-items');
        const items = Object.values(basket);
        if (items.length === 0) {
            box.innerHTML = '<div class="basket-empty">Elige un producto para empezar.</div>';
        } else {
            box.innerHTML = items.map(i => `
                <div class="basket-row">
                    <div class="bi-name">${escapeHtml(i.name)}<small>${fmt(i.price)} c/u</small></div>
                    <div class="qty-ctrl">
                        <button type="button" onclick="changeQty(${i.id}, -1)">−</button>
                        <span>${i.quantity}</span>
                        <button type="button" onclick="changeQty(${i.id}, 1)">+</button>
                    </div>
                    <div class="bi-sub">${fmt(i.price * i.quantity)}</div>
                </div>`).join('');
        }
        const t = totals();
        document.getElementById('f-subtotal').textContent = fmt(t.subtotal);
        document.getElementById('f-tax').textContent = fmt(t.tax);
        document.getElementById('f-total').textContent = fmt(t.total);
        document.getElementById('btn-checkout').disabled = items.length === 0;
    }

    function openModal() {
        const t = totals();
        if (t.count === 0) return;
        document.getElementById('m-count').textContent = t.count + (t.count === 1 ? ' artículo' : ' artículos');
        document.getElementById('m-subtotal').textContent = fmt(t.subtotal);
        document.getElementById('m-tax').textContent = fmt(t.tax);
        document.getElementById('m-total').textContent = fmt(t.total);

        const hidden = document.getElementById('hidden-items');
        hidden.innerHTML = Object.values(basket).map((i, idx) => `
            <input type="hidden" name="items[${idx}][id]" value="${i.id}">
            <input type="hidden" name="items[${idx}][quantity]" value="${i.quantity}">`).join('');

        document.getElementById('modal').classList.add('open');
    }

    function closeModal() { document.getElementById('modal').classList.remove('open'); }

    document.getElementById('modal').addEventListener('click', e => {
        if (e.target.id === 'modal') closeModal();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endpush
