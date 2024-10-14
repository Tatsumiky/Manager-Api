<?php
namespace App\Application\Actions\SeriesAction;

use Psr\Http\Message\ResponseInterface;

class DeleteSeriesAction extends SeriesAction {
    public function action(): ResponseInterface {
        $id = (int) $this->request->getAttribute('id');

        $result = $this->seriesRepo->deleteSeries($id);

        if ($result === 0) {
            return $this->respondWithData(['error' => 'Série não encontrada.'], 404);
        }

        return $this->respondWithData(['message' => 'Série deletada com sucesso.'], 200);
    }
}
