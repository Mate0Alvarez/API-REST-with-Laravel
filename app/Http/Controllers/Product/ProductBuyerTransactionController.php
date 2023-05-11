<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
        $this->middleware('scope:purchase-product')->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product, User $buyer): JsonResponse
    {
        $rules = [
            'quantity' => 'integer|min:1',
        ];
        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse(
                'The buyer must be different from the seller.',
                Response::HTTP_CONFLICT
            );
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse(
                'The buyer must be a verified user.',
                Response::HTTP_CONFLICT
            );
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse(
                'The seller must be a verified user.',
                Response::HTTP_CONFLICT
            );
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse(
                'The product is not available at this moment.',
                Response::HTTP_CONFLICT
            );
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse(
                'The product has not the enough quantity.',
                Response::HTTP_CONFLICT
            );
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, Response::HTTP_CREATED);
        });
    }
}
