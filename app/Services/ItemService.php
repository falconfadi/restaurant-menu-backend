<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ItemService
{
    public function get()
    {
        $userId = \Auth::id();
        return Item::whereHas('category',function($subQ) use($userId) {
            $subQ->where('user_id', $userId);
        })->get();
    }

    public function store($data)
    {
        $userId = \Auth::id();
        $categoryId = $data['category_id'];
        $category = Category::find($categoryId);
        if($category){
            // id category belong to other user
            if ($category->user_id != $userId)
                throw new UnauthorizedException('Not authorized',403);
            // id category has subcategories
            if ($category->subcategories->count()>0)
                throw new BadRequestException('This category is not in the last level');
            return Item::create([
                'title'=> $data['title'],
                'price'=> $data['price'],
                'category_id'=> $categoryId,
                'discount'=> $data['discount']
            ]);
        }
    }

    public function update($id, $data)
    {
        $category = Category::find($id);
        if($category){
            if ($category->user_id != \Auth::id())
                throw new UnauthorizedException('Not authorized',403);
            return $category->update($data);
        }
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category->user_id != \Auth::id())
            throw new UnauthorizedException('UnAuthorized',403);
        if ($category->parent_id == null)
            throw new UnauthorizedException('You can\'t delete the root category');
        return $category->delete();
    }
}
