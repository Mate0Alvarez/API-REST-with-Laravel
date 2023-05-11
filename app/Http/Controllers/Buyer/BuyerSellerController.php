<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer): JsonResponse
    {
        $seller = $buyer
                        ->transactions()
                        ->with('product.seller')
                        ->get()
                        ->pluck('product.seller')
                        ->unique('id')
                        ->values()
                        ->filter();

        return $this->showAll($seller);
    }
}
