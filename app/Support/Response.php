<?php
namespace TorNas\Support;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class Response
{
    /**
     * Create json response
     *
     * @param mixed $data
     * @param int   $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function make($data = [], $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Create transformed response for single model
     *
     * @param Model       $model
     * @param Transformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function item($model, Transformer $transformer)
    {
        return $this->make($transformer->make($model));
    }

    /**
     * Create transformed response for collection of models
     *
     * @param Collection  $models
     * @param Transformer $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection(Collection $models, Transformer $transformer)
    {
        return $this->make($transformer->transform($models));
    }

    /**
     * Create presented response for paginated models
     *
     * @param LengthAwarePaginator $pagination
     * @param Transformer          $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pagination(LengthAwarePaginator $pagination, Transformer $transformer)
    {
        return $this->make($transformer->transform($pagination));
    }

    /**
     * Return 204 http response without content.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function noContent()
    {
        return response('', 204);
    }

    /**
     * Return 202 http response with optional location and content
     *
     * @param null   $location
     * @param string $content
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function accepted($location = null, $content = '')
    {
        return response($content, 202, $location ? compact('location') : []);
    }

    /**
     * Return 201 http response with optional location and content
     *
     * @param null   $location
     * @param string $content
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function created($location = null, $content = '')
    {
        return response($content, 201, $location ? compact('location') : []);
    }

    /**
     * Return validation error response.
     *
     * @param array $messages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validation(array $messages)
    {
        return $this->make(['error' => 'validation_failed', 'messages' => $messages], 433);
    }

    /**
     * Return exception error response.
     *
     * @param Exception $e
     *
     * @return mixed
     */
    public function exception(Exception $e)
    {
        $response = [
            'error' => 'something_wrong'
        ];

        if (config('app.debug')) {
            $response['exception'] = get_class($e);
            $response['message']   = $e->getMessage();
            $response['trace']     = $e->getTrace();
        }

        $statusCode = $e instanceof HttpException ? $e->getStatusCode() : 500;

        return $this->make($response, $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param string $error
     * @param int    $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($error, $statusCode)
    {
        return $this->make(compact('error'), $statusCode);
    }

    /**
     * Return a 404 not found error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorNotFound($message = 'Not Found')
    {
        return $this->error($message, 404);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorBadRequest($message = 'Bad Request')
    {
        return $this->error($message, 400);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->error($message, 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorInternal($message = 'Internal Error')
    {
        return $this->error($message, 500);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        return $this->error($message, 405);
    }
}
