<?php
namespace App\Infrastructure\Repository\SqlRepository;

use PDOException;
use PDOStatement;
use App\Infrastructure\Connection\Sql;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;

class SqlRepository implements SqlInterface {
    public function __construct(public Sql $sql) {}

    public function insert(string $table, array $dados): int
    {
        try {
            $stmt = $this->sql->insert($table, $dados);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $message = $e->getCode() === '23505' || $e->getCode() === '1062'
                ? "Falha ao inserir dados! O campo de email ou o ID de login pode já estar em uso."
                : $e->getMessage();
            throw new PDOException($message);
        }
    }

    public function delete(int|string $id, string $table, string|int $params = 'id'): int
    {
        $stmt = $this->sql->delete($id, $table);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function selectFindAll(string $table): array | null
    {
        $stmt = $this->sql->selectFindAll($table);
        $stmt->execute();
        $r = $stmt->fetchAll(\PDO::FETCH_ASSOC); 
        
        return $r;
    }

    public function selectUserOfId(int|string $id, string $table, string|int $params = 'id'): array | null
    {
        $stmt = $this->sql->selectUserOfId($id, $table, $params);
        $stmt->execute();
        $r = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $r;
    }

    public function update(string|int $id, string $table, array $dados, string|int $params = 'id'): int
    {   
        $stmt = $this->sql->update($id, $table, $dados, $params);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            if ($e->getCode() == '23505' || $e->getCode() == '1062') {
                throw new PDOException("Falha ao atualizar dados! O campo de email ou o ID de login pode já estar em uso.");
            }
            throw new PDOException("Falha ao atualizar dados do usuário");
        }
    }
    public function selectFindAllWhere(string $table, array $conditions): array | null
    {
        $sql = "SELECT * FROM $table WHERE ";
        $sql .= implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $stmt = $this->sql->prepare($sql);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteWhere(string $table, array $conditions): int
    {
        $sql = "DELETE FROM $table WHERE ";
        $sql .= implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $stmt = $this->sql->prepare($sql);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
