<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsCategory;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    //HIển thị tất cả các bài news (theo trang , và số lượng news tùy chọn) (dùng trong trang admin hoặc danh sách trang home)
    public function index(Request $request)
    {
        $news = [];

        $news["count"] = News::all()->count();
        $news["newss"] =  News::take($request->count)->skip(($request->page - 1) * $request->count)->get();

        foreach ($news["newss"] as $item) {
            $item["category"] = News::find($item->id)->category;
            foreach ($item["category"] as $i) {
                unset($i["created_at"]);
                unset($i["updated_at"]);
                unset($i["pivot"]);
            }
            unset($item["content"]);
        }

        return response()->json($news, 200)->header('Content-Type', 'application/json');
    }

    //Hiển thị chi tiết một news
    public function show($path)
    {
        $news = News::where("path", $path)->first();
        $news["category"] = News::find($news->id)->category;
        foreach ($news["category"] as $i) {
            unset($i["created_at"]);
            unset($i["updated_at"]);
            unset($i["pivot"]);
        }

        return response()->json($news, 200)->header('Content-Type', 'application/json');
    }

    //HIển thị danh sách news theo từng thể loại
    public function category($path)
    {
        $news = Category::where("path", $path)->first()->newss;

        foreach ($news as $item) {
            unset($item["pivot"]);
        }

        return response()->json($news, 200)->header('Content-Type', 'application/json');
    }

    //Tạo news mới
    public function create(Request $request)
    {
        $news = new News();

        $news->title  = $request->title;
        $news->text = $request->text;
        $news->author = $request->author;
        $news->image = $request->image;
        $news->path = $request->path;
        $news->save();

        foreach ($request->category as $item) {
            $newsCategory = new NewsCategory();
            $newsCategory->news_id  = $news->id;
            $newsCategory->category_id = $item;
            $newsCategory->save();
        }

        return response()->json($news, 200)->header('Content-Type', 'application/json');
    }

    //Xóa news
    public function delete($id)
    {
        News::destroy($id);

        NewsCategory::where("news_id", $id)->delete();
    }

    //Sửa nội dung news
    public function update(Request $request, $id)
    {
        $news = News::find($id);
        $news->title  = $request->title;
        $news->text = $request->text;
        $news->author = $request->author;
        $news->image = $request->image;
        $news->path = $request->path;
        $news->save();

        NewsCategory::where("news_id", $id)->delete();
        foreach ($request->category as $item) {
            $newsCategory = new NewsCategory();
            $newsCategory->news_id  = $id;
            $newsCategory->category_id = $item;
            $newsCategory->save();
        }

        return response()->json($news, 200)->header('Content-Type', 'application/json');
    }
}
