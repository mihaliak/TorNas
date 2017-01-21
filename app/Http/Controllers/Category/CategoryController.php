<?php
namespace TorNas\Http\Controllers\Category;

use Illuminate\Http\Request;

use TorNas\Http\Controllers\Controller;

use TorNas\Modules\Category\Category;
use TorNas\Modules\Category\CategoryTransformer;

class CategoryController extends Controller
{
    /**
     * Get list of categories
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->response()->collection(Category::all(), new CategoryTransformer());
    }
}
