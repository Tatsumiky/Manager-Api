<?php
namespace App\Infrastructure\Repository\FavoriteRepository;

use App\Infrastructure\Repository\SqlRepository\SqlRepository;

class FavoriteRepository {
    public function __construct(private SqlRepository $sql) {}

    public function addFavorite(int $userId, int $itemId): array {
        try {
            // Verificar se o favorito já existe
            $existingFavorite = $this->sql->selectFindAllWhere('favorites', [
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);

            if ($existingFavorite) {
                return ['error' => 'Item já favoritado.'];
            }

            // Inserir o novo favorito
            $favoriteId = $this->sql->insert('favorites', [
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);

            if ($favoriteId) {
                return ['success' => 'Favorito salvo com sucesso!'];
            }

            return ['error' => 'Erro ao salvar favorito.'];
        } catch (\Throwable $th) {
            return ['error' => 'Erro: ' . $th->getMessage()];
        }
    }

    public function removeFavorite(int $userId, int $itemId, string $itemType): bool {
        try {
            return $this->sql->deleteWhere('favorites', [
                'user_id' => $userId,
                'item_id' => $itemId,
            ]) > 0;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
