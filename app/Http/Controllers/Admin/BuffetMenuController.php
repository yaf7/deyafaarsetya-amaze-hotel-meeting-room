<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuffetMenu;
use Illuminate\Http\Request;

class BuffetMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = BuffetMenu::query();
        
        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        $menus = $query->latest('id')->paginate(15);
        return view('admin.buffet-menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.buffet-menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:soup,mie,ayam,ikan,sayuran,fritter',
        ]);

        BuffetMenu::create($request->only(['name', 'category']));
        return redirect()->route('admin.buffet-menus.index')->with('success', 'Menu buffet berhasil ditambahkan.');
    }

    public function edit(BuffetMenu $buffetMenu)
    {
        return view('admin.buffet-menus.edit', compact('buffetMenu'));
    }

    public function update(Request $request, BuffetMenu $buffetMenu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:soup,mie,ayam,ikan,sayuran,fritter',
        ]);

        $buffetMenu->update($request->only(['name', 'category']));
        return redirect()->route('admin.buffet-menus.index')->with('success', 'Menu buffet berhasil diperbarui.');
    }

    public function destroy(BuffetMenu $buffetMenu)
    {
        $buffetMenu->delete();
        return redirect()->route('admin.buffet-menus.index')->with('success', 'Menu buffet berhasil dihapus.');
    }
}
