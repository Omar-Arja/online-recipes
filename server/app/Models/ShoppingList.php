<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShoppingListItem;
use App\Models\User;

class ShoppingList extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(ShoppingListItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
