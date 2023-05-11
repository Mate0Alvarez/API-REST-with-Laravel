<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Buyer;
use App\Models\Product;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    public $transformer = TransactionTransformer::class;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];

    public function buyer():BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
