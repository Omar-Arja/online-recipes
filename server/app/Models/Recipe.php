<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Image;
use App\Models\Cuisine;
use App\Models\Plan;
use App\Models\RecipeIngredient;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'cuisine_id',
    ];

    protected $appends = [
        'user_name',
        'is_added',
        'is_liked',
        'likes_count',
        'comments_count',
        'ingredients',
        'images',
        'cuisine_name',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'recipe_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class, 'recipe_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function shoppingLists()
    {
        return $this->belongsToMany(ShoppingList::class);
    }

    // Attributes
    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function getIsAddedAttribute()
    {
        if (auth()->user()) {
            $shoppingList = auth()->user()->shoppingList;
            if ($shoppingList) {
                return $shoppingList->items->contains('recipe_id', $this->id);
            }
        }
        return false;
    }

    public function getIsLikedAttribute()
    {
        $user = auth()->user();
        if ($user) {
            return $this->likes->contains('id', $user->id);
        }
    }

    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments->count();
    }

    public function getIngredientsAttribute()
    {
        return $this->ingredients()->get();
    }

    public function getImagesAttribute()
    {
        return $this->images()->get();
    }

    public function getCuisineNameAttribute()
    {
        return $this->cuisine ? $this->cuisine->name : null;
    }
}
