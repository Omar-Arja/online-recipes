<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Cuisine;
use App\Models\Ingredient;
use App\Models\Image;
use App\Models\RecipeIngredient;
use App\Models\Comment;
use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    public function getRecipes($search = null)
    {
        if ($search) {
            $searchBy = $request->search->explode('.')[0];
            $searchValue = $request->search->explode('.')[1];

            $recipes = Recipe::where($searchBy, 'LIKE', "%{$searchValue}%")->get();

        } else {
            $recipes = Recipe::all();
        }

        return response()->json([
            'status' => 'success',
            'recipes' => $recipes,
        ]);
    }

    public function getRecipe($id)
    {
        $recipe = Recipe::find($id);
        return response()->json($recipe);
    }

    public function createRecipe(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cuisine_name' => 'required|string|max:255',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.ammount' => 'required|string|max:255',
            'images' => 'required|array',
        ]);

        $cuisine = Cuisine::firstOrCreate([
            'name' => $request->cuisine_name,
        ]);

        $recipe = new Recipe();
        $recipe->name = $request->name;
        $recipe->user_id = Auth::id();
        $recipe->cuisine_id = $cuisine->id;
        $recipe->save();

        foreach ($request->ingredients as $ingredient) {
            $ingredient = new Ingredient();
            $ingredient->name = $ingredient['name'];
            $ingredient->save();

            $recipe_ingredient = new RecipeIngredient();
            $recipe_ingredient->recipe_id = $recipe->id;
            $recipe_ingredient->ingredient_id = $ingredient->id;
            $recipe_ingredient->ammount = $ingredient['ammount'];
            $recipe_ingredient->save();
        }

        foreach ($request->images as $image) {
            $image_name = time() . '_' . uniqid() .$image->getClientOriginalName();
            $image->move(public_path('images'), $image_name);
        
            $recipe_image = new Image();
            $recipe_image->recipe_id = $recipe->id;
            $recipe_image->image_url = asset('images/' . $image_name);
            $recipe_image->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'recipe' => $recipe,
        ]);
    }

    public function deleteRecipe($id)
    {
        $recipe = Recipe::find($id);
        if (!$recipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
            ], 404);
        }
        
        $recipe->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe deleted successfully',
        ]);
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $recipe = Recipe::find($id);
        if (!$recipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
            ], 404);
        }

        $comment = new Comment();
        $comment->recipe_id = $recipe->id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ]);
    }

    public function likeRecipe($id)
    {
        $user = Auth::user();
        $recipe = Recipe::find($id);
        if (!$recipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
            ], 404);
        }

        $recipe->likes()->toggle($user->id);
        
        if ($recipe->likes->contains($user->id)) {
            $message = 'Recipe liked successfully';
        } else {
            $message = 'Recipe unliked successfully';
        }
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    public function addRecipeToShoppingList($id)
    {
        $user = Auth::user();
        $recipe = Recipe::find($id);
        if (!$recipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
            ], 404);
        }

        if (!$user->shoppingList) {        
            $shoppingList = new ShoppingList();
            $shoppingList->user_id = $user->id;
            $shoppingList->save();
        }

        if ($user->shoppingList->items->contains($recipe->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe already added to shopping list',
            ], 400);
        }

        $user->shoppingList->items()->attach($recipe->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe added to shopping list successfully',
        ]);   
    }

    public function removeRecipeFromShoppingList($id)
    {
        $user = Auth::user();
        $recipe = Recipe::find($id);
        if (!$recipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
            ], 404);
        }

        if (!$user->shoppingList) {        
            return response()->json([
                'status' => 'error',
                'message' => 'Shopping list not found',
            ], 404);
        }

        if (!$user->shoppingList->items->contains($recipe->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found in shopping list',
            ], 404);
        }

        $user->shoppingList->items()->detach($recipe->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe removed from shopping list successfully',
        ]);   
    }

}
