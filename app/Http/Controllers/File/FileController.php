<?php
namespace TorNas\Http\Controllers\File;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use TorNas\Http\Controllers\Controller;
use TorNas\Http\Requests\File\StoreSubtitlesRequest;
use TorNas\Http\Requests\File\UpdateFileRequest;

use TorNas\Modules\Category\Category;
use TorNas\Modules\File\FileRepository;
use TorNas\Modules\File\FileTransformer;

class FileController extends Controller
{
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * FileController constructor.
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Get list of files
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $files           = $this->fileRepository->getSorted()->groupBy('name');
        $filesWithSeries = new Collection();
        $transformer     = new FileTransformer();

        $files->each(function ($parts) use ($transformer, $filesWithSeries) {
            $item = $transformer->transform($parts->last());

            if ($parts->count() != 1 || $item['category']['value'] == 'series') {
                $item['series'] = $transformer->transform($parts);
            }

            $filesWithSeries->push($item);
        });

        return $this->response()->make($filesWithSeries->toArray());
    }

    /**
     * Update file data
     *
     * @param                   $id
     * @param UpdateFileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateFileRequest $request)
    {
        $category = Category::findOrFail($request->category);

        $attributes = [
            'name'        => $request->name,
            'episode'     => $request->episode,
            'year'        => $request->year,
            'rating'      => $request->rating,
            'runtime'     => $request->runtime,
            'category_id' => $category->id,
        ];

        $file = $this->fileRepository->updateFile($id, $attributes)
                                     ->syncGenres($request->genres)
                                     ->addSubtitles('subtitles.lang', 'subtitles.subtitles', 'subtitles.episode');

        if ($request->has('cover') && ! empty(trim($request->cover))) {
            $file = $file->clearCover()->addCover($request->cover);
        }

        return $this->response()->item($file->file, new FileTransformer());
    }

    /**
     * Remove file and torrent files
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function remove($id)
    {
        $this->fileRepository->remove($id);

        return $this->response()->noContent();
    }
}
