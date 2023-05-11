<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
    public function transform(Product $product)
    {
        return [
            'identifier' => (int) $product->id,
            'title' => (string) $product->name,
            'details' => (string) $product->description,
            'availableQuantity' => (int) $product->quantity,
            'productStatus' => (string) $product->status,
            'productImage' => url('img/' . $product->image),
            'productSeller' => (int) $product->seller_id,
            'createDate' => (string) $product->created_at,
            'updateDate' => (string) $product->updated_at,
            'deleteDate' => isset($product->deleted_at) ? (string) $product->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id),
                ],
            ],
        ];
    }

    public static function originalAttribute(string $index)
    {
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'availableQuantity' => 'quantity',
            'productStatus' => 'status',
            'productImage' => 'image',
            'productSeller' => 'seller_id',
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
            'name'       => 'title',
            'description'=> 'details',
            'quantity'   => 'availableQuantity',
            'status'     => 'productStatus',
            'image'      => 'productImage',
            'seller_id'  => 'productSeller',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
