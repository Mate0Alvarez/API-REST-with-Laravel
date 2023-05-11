<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->showAll(Buyer::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyer $buyer): JsonResponse
    {
        return $this->showOne($buyer);
    }
}
