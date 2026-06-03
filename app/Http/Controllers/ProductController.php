<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q'));

        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->storePhoto($request);

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto agregado. La tabla ya se actualizó.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($product->image);
            $data['image'] = $this->storePhoto($request);
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $this->deletePhoto($product->image);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'sku'         => ['required', 'string', 'max:40', 'unique:products,sku' . ($id ? ",{$id}" : '')],
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'photo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'sku.required'   => 'La clave del producto es obligatoria.',
            'sku.unique'     => 'Ya existe un producto con esa clave.',
            'name.required'  => 'El nombre es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'photo.image'    => 'El archivo debe ser una imagen.',
        ]);
    }

    /** OneDrive marca is_writable() como falso aunque sí se puede escribir; copy() evita ese problema. */
    private function storePhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $file   = $request->file('photo');
        $folder = public_path('img/products');

        if (! is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $extension = $file->extension() ?: $file->getClientOriginalExtension();
        $filename  = uniqid('item_') . '.' . $extension;

        copy($file->getRealPath(), $folder . DIRECTORY_SEPARATOR . $filename);

        return 'img/products/' . $filename;
    }

    private function deletePhoto(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
