<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Http\Resources\ProduitResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $produits = Produit::all();
        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully.',
            'data' => ProduitResource::collection($produits)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'detail' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 400);
        }

        $produit = Produit::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => new ProduitResource($produit)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $produit = Produit::find($id);

        if (is_null($produit)) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully.',
            'data' => new ProduitResource($produit)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Produit $produit): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'detail' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 400);
        }

        $produit->name = $input['name'];
        $produit->detail = $input['detail'];
        $produit->save();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => new ProduitResource($produit)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Produit $produit): JsonResponse
    {
        $produit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
            'data' => []
        ], 200);
    }
}
