<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Resources\CategoryResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use HTTPResponse;
    public function gelAllCategories(){
        try {
            $categories = Category::all();
            return $this->success(CategoryResource::collection($categories));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
    public function visibleCategories(){
        try {
            $categories = Category::where('is_visible' , true)->get();
            return $this->success(CategoryResource::collection($categories));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function switchVisibility($categoryID){
        try {
            $category = HelperFunction::getCategoryByID($categoryID);
            if (!$category) {
                return $this->error('category does\'nt found in our system' , 404);
            }
            $category->update([
                'is_visible' => !$category->is_visible
            ]);
            return $this->success(CategoryResource::make($category) , $category->name . ' updated successfully');
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function store(StoreCategoryRequest $request){
        $request->validated($request->only(['name','is_visible']));
        try {
            $category = Category::create($request->only(['name','is_visible']));
            return $this->success(CategoryResource::make($category) , $category->name . ' created successfully');
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function updateCategory(UpdateCategoryRequest $request , $categoryID){
        try {
            $category = HelperFunction::getCategoryByID($categoryID);
            if (!$category) {
                return $this->error('category does\'nt found in our system' , 404);
            }
            $request->validated($request->only(['name' , 'is_visible']));
            $category->update($request->only(['name' , 'is_visible']));

            return $this->success(CategoryResource::make($category) , $category->name . ' updated successfully');
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function destroy($categoryID){
        $category = HelperFunction::getCategoryByID($categoryID);
        if (!$category) {
            return $this->error('category does\'nt found in our system' , 404);
        }
        try {
            $category->delete();
            return $this->success(CategoryResource::make($category) ,$category->name .' deleted successfully' );
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
}
