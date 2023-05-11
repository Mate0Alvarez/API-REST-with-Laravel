<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class SellerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view,seller')->only('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller): JsonResponse
    {
        return $this->showAll(
            $seller
                ->products()
                ->with('categories')
                ->get()
                ->pluck('categories')
                ->collapse()
                ->unique('id')
                ->values()
                ->filter()
        );
    }
}
