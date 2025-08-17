<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CatalogProductController extends Controller
{
    public function show(Catalog $catalog)
    {
        // pobieramy produkty wraz z pivot (cena)
        $catalog->load(['products' => function ($query) {
            $query->withPivot('price');
        }]);

        return Inertia::render('CatalogShow', [
            'authUser' => Auth::user(),
            'catalog' => $catalog,
            'products' => $catalog->products,
        ]);
    }

    public function store(Request $request, Catalog $catalog)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
        ]);

        $catalog->products()->syncWithoutDetaching([
            $request->product_id => ['price' => $request->price],
        ]);

        return redirect()->back();
    }

    public function destroy(Catalog $catalog, Product $product)
    {
        $catalog->products()->detach($product->id);

        return redirect()->back();
    }
}
