<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::latest()->paginate(10),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $name = $request->validated('name');

        Category::create([
            'name' => $name,
            'slug' => Str::slug($name.'-'.Str::random(5)),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $name = $request->validated('name');

        $category->update([
            'name' => $name,
            'slug' => Str::slug($name.'-'.Str::random(5)),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => 'Kategori tidak bisa dihapus karena masih punya produk.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
