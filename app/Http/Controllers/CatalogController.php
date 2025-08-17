<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index()
    {

        $catalogs = Catalog::withCount('products')->get();

        return Inertia::render('Catalogs', [
            'authUser' => Auth::user(),
            'catalogs' => $catalogs,
        ]);
    }

    public function destroy(Catalog $catalog)
    {

        $catalog->delete();

        return redirect()->back();
    }
}
