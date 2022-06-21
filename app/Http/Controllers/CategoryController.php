<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
            'description' => 'required|string',
            'cost' => 'required'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->cost = $request->cost;
        $category->save();
        return $this->returnSuccessMessage();
    }

    public function edit(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
            'description' => 'required|string',
            'cost' => 'required',
            'id' =>   'required|int'
        ]);

        $category = Category::find($request->id);
        if( isset($category) )
        {
            $category->name = $request->name;
            $category->description = $request->description;
            $category->cost = $request->cost;
            $category->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError("category not found");

    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $category = Category::find($id);
        if( isset($category) )
        {
            $category->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("category not found");

    }

    public function GetAllCategories(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::paginate();
        if( isset($categories) )
        {
            return $this->returnData("get all categories",$categories);
        }else return $this->returnError("categories not found");
    }
}
