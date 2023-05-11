<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int) $transaction->id,
            'transactionQuantity' => (int) $transaction->quantity,
            'transactionBuyer' => (int) $transaction->buyer_id,
            'transactionProduct' => (int) $transaction->product_id,
            'createDate' => (string) $transaction->created_at,
            'updateDate' => (string) $transaction->updated_at,
            'deleteDate' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $transaction->product_id),
                ],
            ],
        ];
    }

    public static function originalAttribute(string $index)
    {
        $attributes = [
            'identifier' => 'id',
            'transactionQuantity' => 'quantity',
            'transactionBuyer' => 'buyer_id',
            'transactionProduct' => 'product_id',
            'createDate' => 'created_at',
            'updateDate' => 'updated_at',
            'deleteDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformAttribute(string $index)
    {
        $attributes = [
            'id' => 'identifier',
            'quantity' => 'transactionQuantity',
            'buyer_id' => 'transactionBuyer',
            'product_id' => 'transactionProduct',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
