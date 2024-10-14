<?php
namespace App\Application\Actions\SeriesAction;

use Psr\Http\Message\ResponseInterface;

class UpdateSeriesAction extends SeriesAction {
    public function action(): ResponseInterface {
        $id = (int) $this->request->getAttribute('id');
        $seriesData = $this->request->getParsedBody()['series'] ?? null;

        if (!$seriesData) {
            return $this->respondWithData(['error' => 'Dados inválidos.'], 400);
        }

        $result = $this->seriesRepo->updateSeries($id, $seriesData);

        if ($result === 0) {
            return $this->respondWithData(['error' => 'Série não encontrada ou não houve alteração.'], 404);
        }

        return $this->respondWithData(['message' => 'Série atualizada com sucesso.'], 200);
    }
}
