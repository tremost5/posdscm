<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@posdscm.test',
        ], [
            'name' => 'Admin POS',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate([
            'email' => 'kasir@posdscm.test',
        ], [
            'name' => 'Kasir POS',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        $foods = Category::updateOrCreate(
            ['name' => 'Makanan'],
            ['slug' => Str::slug('Makanan')]
        );

        $drinks = Category::updateOrCreate(
            ['name' => 'Minuman'],
            ['slug' => Str::slug('Minuman')]
        );

        $supplierA = Supplier::updateOrCreate(
            ['name' => 'CV Sumber Rasa'],
            ['phone' => '081234567890', 'address' => 'Bandung', 'is_active' => true]
        );

        $supplierB = Supplier::updateOrCreate(
            ['name' => 'PT Segar Makmur'],
            ['phone' => '082233445566', 'address' => 'Jakarta', 'is_active' => true]
        );

        Product::updateOrCreate(
            ['sku' => 'MKN-001'],
            ['category_id' => $foods->id, 'supplier_id' => $supplierA->id, 'name' => 'Nasi Goreng', 'cost_price' => 15000, 'price' => 22000, 'stock' => 60, 'is_active' => true]
        );
        Product::updateOrCreate(
            ['sku' => 'MKN-002'],
            ['category_id' => $foods->id, 'supplier_id' => $supplierA->id, 'name' => 'Mie Ayam', 'cost_price' => 12000, 'price' => 18000, 'stock' => 50, 'is_active' => true]
        );
        Product::updateOrCreate(
            ['sku' => 'MNM-001'],
            ['category_id' => $drinks->id, 'supplier_id' => $supplierB->id, 'name' => 'Es Teh', 'cost_price' => 3500, 'price' => 7000, 'stock' => 80, 'is_active' => true]
        );
        Product::updateOrCreate(
            ['sku' => 'MNM-002'],
            ['category_id' => $drinks->id, 'supplier_id' => $supplierB->id, 'name' => 'Kopi Susu', 'cost_price' => 8000, 'price' => 14000, 'stock' => 40, 'is_active' => true]
        );
    }
}
