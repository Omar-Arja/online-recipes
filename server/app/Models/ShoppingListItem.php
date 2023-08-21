<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShoppingList;
use App\Models\Recipe;

class ShoppingListItem extends Model
{
    use HasFactory;

    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
