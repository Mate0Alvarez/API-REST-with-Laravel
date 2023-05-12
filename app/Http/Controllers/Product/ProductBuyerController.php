<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.

     */
    public function index(Product $product): JsonResponse
    {
        $this->allowAdminAction();

        return $this->showAll(
            $product
                ->transactions()
                ->with('buyer')
                ->get()
                ->pluck('buyer')
                ->unique('id')
                ->values()
                ->filter()
        );
    }
}
