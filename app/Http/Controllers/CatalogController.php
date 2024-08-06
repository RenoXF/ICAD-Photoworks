<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (empty(auth()->user()) || auth()->user()?->role === RoleEnum::Client) {
            $products = Product::query()->paginate(12);

            return view('catalog.index', compact('products'));
        }

        $products = Product::query()->paginate(20);

        return view('catalog.manage', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'title' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        $foto = $request->file('image')->store('barang', 'public');

        if (!$foto) {
            return redirect()
                ->back()
                ->withErrors([
                    'foto' => 'Gambar gagal diupload',
                ]);
        }

        $data['image'] = 'storage/' . $foto;

        try {
            Product::create($data);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors([
                    'foto' => $th->getMessage(),
                ]);
        }

        return redirect()->route('catalog.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()
                ->route('catalog.index')
                ->withErrors([
                    'barang' => 'Data produk tidak ditemukan',
                ]);
        }

        $data = $request->validate([
            'type' => 'required',
            'title' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if (empty($data['image'])) {
            unset($data['image']);
        } else {
            $foto = $request->file('image')->store('barang', 'public');

            if (!$foto) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'foto' => 'Gambar gagal diupload',
                    ]);
            }

            $data['image'] = 'storage/' . $foto;
        }

        try {
            $product->update($data);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors([
                    'foto' => $th->getMessage(),
                ]);
        }

        return redirect()->route('catalog.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()
                ->route('catalog.index')
                ->withErrors([
                    'barang' => 'Data produk tidak ditemukan',
                ]);
        }

        try {
            $product->delete();
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors([
                    'barang' => $th->getMessage(),
                ]);
        }
        return redirect()->route('catalog.index')->with('success', 'Data berhasil dihapus');
    }
}
