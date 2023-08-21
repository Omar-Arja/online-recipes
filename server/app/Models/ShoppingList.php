<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShoppingListItem;
use App\Models\Recipe;
use App\Models\User;

class ShoppingList extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->belongsToMany(Recipe::class, 'shopping_list_items', 'shopping_list_id', 'recipe_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
