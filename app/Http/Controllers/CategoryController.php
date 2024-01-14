<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MedicineResource;

class CategoryController extends Controller
{

    public function index()
    {
        return CategoryResource::collection(Category::paginate()->items());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated()) ;

        return new CategoryResource($category) ;
    }
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null , 204) ;
    }


    public function medicinesByCategory(Category $category)
    {
        return MedicineResource::collection($category->medicines);
    }

}
