<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Daftar kategori kendala + pencarian live
    public function index(Request $request)
    {
        $categories = Category::withCount('tickets')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->q}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->boolean('ajax')) {
            return response()->json([
                'data' => $categories->getCollection()->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'tickets_count' => $c->tickets_count,
                    'created_at' => $c->created_at->format('d M Y, H:i'),
                    'edit_url' => route('admin.categories.edit', $c->id),
                    'destroy_url' => route('admin.categories.destroy', $c->id),
                ])->values(),
                'pagination' => $categories->links()->toHtml(),
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ]);
        }

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name'], $category->id),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $category = Category::withCount('tickets')->findOrFail($id);

        // Proteksi: kategori yang masih dipakai tiket tidak boleh dihapus
        // (relasi memakai onDelete cascade, jadi hapus di sini = tiket ikut hilang)
        if ($category->tickets_count > 0) {
            return redirect()->back()->with(
                'error',
                "Tidak dapat menghapus \"{$category->name}\": masih dipakai oleh {$category->tickets_count} tiket."
            );
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    // Slug unik otomatis: "jaringan-internet", "jaringan-internet-2", dst.
    protected function uniqueSlug(string $name, int $exceptId = 0): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $counter = 1;

        while (Category::where('slug', $slug)->where('id', '!=', $exceptId)->exists()) {
            $slug = $base . '-' . (++$counter);
        }

        return $slug;
    }
}
