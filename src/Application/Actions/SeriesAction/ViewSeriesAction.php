<?php
namespace App\Application\Actions\SeriesAction;

use Psr\Http\Message\ResponseInterface;

class ViewSeriesAction extends SeriesAction {
    public function action(): ResponseInterface {
        $id = (int) $this->request->getAttribute('id');

        $series = $this->seriesRepo->getSeriesById($id);

        if (!$series) {
            return $this->respondWithData(['error' => 'Série não encontrada.'], 404);
        }

        return $this->respondWithData($series);
    }
}
