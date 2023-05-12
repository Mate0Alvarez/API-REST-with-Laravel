<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('show');
        $this->middleware('can:view,seller')->only('show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->allowAdminAction();
        
        return $this->showAll(Seller::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller): JsonResponse
    {
        return $this->showOne($seller);
    }
}
