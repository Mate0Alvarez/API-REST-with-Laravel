<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer): JsonResponse
    {
        $categories = $buyer
            ->transactions()
            ->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values()
            ->filter();

        return $this->showAll($categories);
    }
}
