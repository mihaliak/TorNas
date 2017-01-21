<?php
namespace TorNas\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use TorNas\Support\Response;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var null|Response
     */
    protected $response = null;

    /**
     * Create Response instance
     *
     * @return Response
     */
    public function response()
    {
        if (is_null($this->response)) {
            $this->response = app(Response::class);
        }

        return $this->response;
    }
}
