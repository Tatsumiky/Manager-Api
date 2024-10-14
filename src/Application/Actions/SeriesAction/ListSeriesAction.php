<?php
namespace App\Application\Actions\SeriesAction;

use Psr\Http\Message\ResponseInterface;

class ListSeriesAction extends SeriesAction {
    public function action(): ResponseInterface {
        $series = $this->seriesRepo->getAllSeries();

        return $this->respondWithData($series);
    }
}
