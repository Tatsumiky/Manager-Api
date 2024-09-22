<?php
namespace App\Application\Actions\FavoritesAction;

use Psr\Http\Message\ResponseInterface;
use App\Infrastructure\Repository\FavoriteRepository\FavoriteRepository;
use App\Application\Actions\Action;

class SaveFavoriteAction extends FavoritesAction {
    protected FavoriteRepository $favoriteRepo;

    public function __construct(FavoriteRepository $favoriteRepo)
    {
        $this->favoriteRepo = $favoriteRepo;
    }

    public function action(): ResponseInterface {
        $body = $this->request->getParsedBody();
        $itemId = $body['item_id'] ?? null;
        $userId = $body['user_id'] ?? null;
        //$userId = $this->request->getAttribute('user_id');

        if (!$itemId || !$userId) {
            return $this->respondWithData(['error' => 'Dados inválidos.'], 400);
        }

        $result = $this->favoriteRepo->addFavorite($userId, $itemId);

        if (isset($result['error'])) {
            return $this->respondWithData($result, 400);
        }

        return $this->respondWithData($result, 201);
    }
}
