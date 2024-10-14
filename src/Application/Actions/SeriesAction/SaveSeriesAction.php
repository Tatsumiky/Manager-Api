<?php
namespace App\Application\Actions\SeriesAction;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Infrastructure\Repository\SqlRepository\SeriesRepository;

class SaveSeriesAction extends SeriesAction {
    public function action(): ResponseInterface {
        $body = $this->request->getParsedBody();
        $seriesData = $body['series'] ?? null;

        if (!$seriesData) {
            return $this->respondWithData(['error' => 'Dados inválidos.'], 400);
        }

        $result = $this->seriesRepo->addSeries($seriesData);

        return $this->respondWithData(['id' => $result], 201);
    }
}
