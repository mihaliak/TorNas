<?php
namespace TorNas\Http\Controllers\Stats;

use TorNas\Http\Controllers\Controller;

use TorNas\Modules\Statistics\Statistics;
use TorNas\Modules\Statistics\StatisticsTransformer;

class StatsController extends Controller
{
    /**
     * Get list of genres
     *
     * @param Statistics $statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Statistics $statistics)
    {
        return $this->response()->item($statistics, new StatisticsTransformer());
    }
}
