<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Récupère l'ID de session unique pour le panier.
     * S'il n'existe pas, il en crée un.
     */
    private function getSessionId(): string
    {
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => Str::uuid()->toString()]);
        }

        return session('cart_session_id');
    }

    /**
     * Affiche les articles du panier.
     * Calcule également le total et les taxes.
     */
    public function index()
    {
        $sessionId = $this->getSessionId();
        $cartItems = CartItem::where('session_id', $sessionId)->with('product')->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $taxRate = 0.10; // Taxe de 10%
        $taxes = $subtotal * $taxRate;
        $total = $subtotal + $taxes;

        // Plus tard, vous retournerez une vue Blade ici.
        return view('cart.index', compact('cartItems', 'subtotal', 'taxes', 'total'));
    }

    /**
     * Ajoute un produit au panier.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'extras' => 'nullable|array'
        ]);

        $sessionId = $this->getSessionId();
        $quantity = $request->input('quantity', 1);
        $extras = $request->input('extras');

        // Vérifie si l'article (avec les mêmes extras) est déjà dans le panier
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->where('extras', $extras ? json_encode($extras) : null)
            ->first();

        if ($cartItem) {
            // Met à jour la quantité
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Crée un nouvel article dans le panier
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'extras' => $extras,
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier !');
    }

    /**
     * Met à jour la quantité d'un article du panier.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->session_id !== $this->getSessionId()) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantité mise à jour !');
    }

    /**
     * Supprime un article du panier.
     */
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->session_id !== $this->getSessionId()) {
            abort(403, 'Action non autorisée.');
        }

        $cartItem->delete();

        return back()->with('success', 'Produit retiré du panier !');
    }
}

