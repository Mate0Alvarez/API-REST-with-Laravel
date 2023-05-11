<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);

        $this->middleware('scope:manage-products')->except('index');
        $this->middleware('can:add-category,product')->only('update');
        $this->middleware('can:delete-category,product')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product): JsonResponse
    {
        return $this->showAll(
            $product->categories
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Product $product, Category $category): JsonResponse
    {
        $product->categories()->syncWithoutDetaching($category->id);

        return $this->showAll(
            $product->categories
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category): JsonResponse
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse(
                'The specified category does not belong to the specified product.',
                Response::HTTP_NOT_FOUND
            );
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll(
            $product->categories
        );
    }
}
