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

class Recipe extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class)->withPivot('amount');
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
}
