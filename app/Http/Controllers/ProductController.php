<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('Hello');
        $products = Product::latest()->get()->map(fn($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'featured_image' => $product->featured_image,
            'featured_image_original_download' => $product->featured_image_original_download,
            'created_at' => $product->created_at->format('d M Y'),

        ]);
        return Inertia::render('products/index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd('Hello create');
        return Inertia::render('products/product-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {

        try {
            $featuredImage = null;
            $featuredOriginalName = null;

            if ($request->file('featured_image')) {
                $featuredImage = $request->file('featured_image');
                $featuredOriginalName = $featuredImage->getClientOriginalName();
                $featuredImage = $featuredImage->store('products', 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'featured_image' => $featuredImage,
                'featured_image_original_download' => $featuredOriginalName,
            ]);

            if ($product) {
                return redirect()->route('products.index')->with('success', 'Product created successfully.');
            } else {
                return redirect()->back()->with('error', 'Unable to created product. Please try again.');
            }
        } catch (Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // dd($product);
        return Inertia::render('products/product-form', [
            'product' => $product,
            'isView' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('products/product-form', [
            'product' => $product,
            'isEdit' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        dd($request->all(), $product);
        // try {
        //     // dd($request->all(), $product);
        //     if ($product) {
        //         $product->name = $request->name;
        //         $product->description = $request->description;
        //         $product->price = $request->price;

        //         if ($request->file('featured_image')) {
        //             $featuredImage = $request->file('featured_image');
        //             $featuredOriginalName = $featuredImage->getClientOriginalName();
        //             $featuredImage = $featuredImage->store('products', 'public');

        //             $product->featured_image = $featuredImage;
        //             $product->featured_image_original_download = $featuredOriginalName;
        //         }

        //         $product->save();

        //         return redirect()->route('products.index')->with('success', 'Product updated successfully');
        //     }
        //     return redirect()->back()->with('error', 'Unable to update product. Please try again!');
        // } catch (Exception $e) {
        //     Log::error('Product update  failed: ' . $e->getMessage());
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product) {
                $product->delete();

                return redirect()->route('products.index')->with('success', 'Product deleted successfully');
            }

            return redirect()->back()->with('error', 'Unable to delete product. Product not found!');
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Product deletion failed: ' . $e->getMessage());

            // Return error message to the user
            return redirect()->route('products.index')->with('error', 'Unable to delete product. Please try again!');
        }
    }
}
