<?php 
namespace App\Infrastructure\Repository\SqlRepository;

interface SqlInterface {
    public function insert(string $table, array $dados): int;
    public function delete(int|string $id, string $table, string|int $params = 'id'): int;
    public function selectFindAll(string $table): array | null;
    public function selectFindAllWhere(string $table, array $conditions): array | null;
    public function deleteWhere(string $table, array $conditions): int;
    public function selectUserOfId(int|string $id, string $table, string|int $params = 'id'): array | null;
    public function update(int $id, string $table, array $dados, string|int $params = 'id'): int;
}
