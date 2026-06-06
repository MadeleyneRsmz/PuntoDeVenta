@php
    $p = $product ?? null;
@endphp
<div class="form-grid">
    <div class="field">
        <label for="sku">Clave *</label>
        <input type="text" id="sku" name="sku" value="{{ old('sku', $p->sku ?? '') }}" required>
        @error('sku') <span class="err">{{ $message }}</span> @enderror
    </div>
    <div class="field">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" value="{{ old('name', $p->name ?? '') }}" required>
        @error('name') <span class="err">{{ $message }}</span> @enderror
    </div>
    <div class="field">
        <label for="price">Precio *</label>
        <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $p->price ?? '') }}" required>
        @error('price') <span class="err">{{ $message }}</span> @enderror
    </div>
    <div class="field">
        <label for="stock">Existencia *</label>
        <input type="number" min="0" id="stock" name="stock" value="{{ old('stock', $p->stock ?? 0) }}" required>
        @error('stock') <span class="err">{{ $message }}</span> @enderror
    </div>
    <div class="field">
        <label for="category_id">Categoría</label>
        <select id="category_id" name="category_id">
            <option value="">Sin categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $p->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="field full">
        <label for="description">Descripción</label>
        <textarea id="description" name="description">{{ old('description', $p->description ?? '') }}</textarea>
    </div>
    <div class="field full">
        <label>Foto del producto</label>
        <label class="dropzone" for="photo">
            <x-icon n="image" style="width:24px;height:24px;color:var(--ink-soft);" />
            <span class="hint">Toca para elegir una imagen (jpg, png o webp)</span>
            <img class="preview" id="photo-preview" alt="">
        </label>
        <input type="file" id="photo" name="photo" accept="image/png,image/jpeg,image/webp" style="display:none;" onchange="mostrarVistaPrevia(this)">
        @error('photo') <span class="err">{{ $message }}</span> @enderror
        @if ($p && $p->image)
            <span class="hint">Ya tiene una foto guardada; elige otra solo si quieres cambiarla.</span>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function mostrarVistaPrevia(input) {
        const img = document.getElementById('photo-preview');
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            img.style.display = 'block';
        }
    }
</script>
@endpush
