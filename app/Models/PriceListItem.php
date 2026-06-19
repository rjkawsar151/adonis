<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceListItem extends Model
{
    protected $table = 'price_list_items';

    protected $fillable = [
        'category',
        'name',
        'price',
        'description',
        'branch_id',
        'sort_order',
    ];
}
