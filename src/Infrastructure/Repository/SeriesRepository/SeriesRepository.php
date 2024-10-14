<?php
namespace App\Infrastructure\Repository\SqlRepository;

class SeriesRepository extends SqlRepository {
    public function addSeries(array $data): int {
        return $this->insert('series', $data);
    }

    public function getAllSeries(): array {
        return $this->selectFindAll('series');
    }

    public function getSeriesById(int $id): array {
        return $this->selectUserOfId($id, 'series');
    }

    public function updateSeries(int $id, array $data): int {
        return $this->update($id, 'series', $data);
    }

    public function deleteSeries(int $id): int {
        return $this->delete($id, 'series');
    }
}
