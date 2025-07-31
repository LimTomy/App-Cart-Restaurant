<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'utilisateur test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Création des catégories et produits
        $categories = Category::factory(5)->create();
        
        $categories->each(function ($category) {
            Product::factory(rand(4, 10))->create([
                'category_id' => $category->id
            ]);
        });

        // 3. Création des items de panier
        CartItem::factory(20)->create([
            'session_id' => 'test_session_' . rand(1, 3) // 3 sessions différentes
        ]);
    }
}