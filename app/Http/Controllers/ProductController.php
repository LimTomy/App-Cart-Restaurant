<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits, avec filtres et recherche.
     */
    public function index(Request $request)
    {
        $productsQuery = Product::query()->where('is_active', true);

        // Filtrage par catégorie
        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->category_id);
        }

        // Recherche de produits
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $productsQuery->with('category')->latest()->paginate(12);
        $categories = Category::all();

        // Plus tard, vous retournerez une vue Blade ici.
        return view('products.index', compact('products', 'categories'));
    }
}

