<?php

namespace App\Transformers;

use App\Models\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int) $buyer->id,
            'userName' => (string) $buyer->name,
            'userEmail' => (string) $buyer->email,
            'userVerified' => (int) $buyer->verified,
            'createDate' => (string) $buyer->created_at,
            'updateDate' => (string) $buyer->updated_at,
            'deleteDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('buyers.show', $buyer->id),
                ],
                [
                    'rel' => 'buyer.categories',
                    'href' => route('buyers.categories.index', $buyer->id),
                ],
                [
                    'rel' => 'buyer.products',
                    'href' => route('buyers.products.index', $buyer->id),
                ],
                [
                    'rel' => 'buyer.sellers',
                    'href' => route('buyers.sellers.index', $buyer->id),
                ],
                [
                    'rel' => 'buyer.transactions',
                    'href' => route('buyers.transactions.index', $buyer->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $buyer->id),
                ],
            ],
        ];
    }

    public static function originalAttribute(string $index)
    {
        $attributes = [
            'identifier' => 'id',
            'userName' => 'name',
            'userEmail' => 'email',
            'userVerified' => 'verified',
            'createDate' => 'created_at',
            'updateDate' => 'updated_at',
            'deleteDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformAttribute(string $index)
    {
        $attributes = [
            'id'         => 'identifier',
            'name'       => 'userName',
            'email'      => 'userEmail',
            'verified'   => 'userVerified',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
