<?php
namespace TorNas\Http\Controllers\Genre;

use Illuminate\Http\Request;

use TorNas\Http\Controllers\Controller;

use TorNas\Modules\Genre\Genre;
use TorNas\Modules\Genre\GenreTransformer;

class GenreController extends Controller
{
    /**
     * Get list of genres
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->response()->collection(Genre::all(), new GenreTransformer());
    }
}
