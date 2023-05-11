<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategorySellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category): JsonResponse
    {
        return $this->showAll(
            $category
                    ->products()
                    ->with('seller')
                    ->get()
                    ->pluck('seller')
                    ->unique('id')
                    ->values()
                    ->filter()
        );
    }
}
