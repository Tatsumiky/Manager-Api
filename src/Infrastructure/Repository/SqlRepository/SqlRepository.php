<?php
namespace App\Infrastructure\Repository\SqlRepository;

use App\classes\CreateLogger;

use App\Infrastructure\Connection\Sql;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;
use App\Infrastructure\Repository\Boletim_cirurgia_Repository\BoletimCirurgiaRepository;
use PDOStatement;

class SqlRepository implements SqlInterface { 
   public function __construct
    (
        public Sql $sql
    ){}
    
    public function insert($table,$dados) :int
    {
        try {
            
            $stmt = $this->sql->insert($table,$dados);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
                if ($e->getCode() == '23505') {
                    

                    throw new \PDOException("Falha ao inserir dados! O campo de email ou o ID de login pode já estar em uso.");
                  }
            throw new \PDOException($e->getMessage());
            // return 0;
        }
   }

    public function delete(int|string $id,string $table,string|int $params = 'id'):int
    {
        $stmt= $this->sql->delete($id,$table);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function selectFindAll(string $table) :array| null
    {
        $stmt = $this->sql->selectFindAll($table);
        $stmt->execute();
        $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
        
        return $r;
    }

    public function selectUserOfId(int|string $id,string $table,string|int $params='id') :array |null
    {
        $stmt = $this->sql->selectUserOfId($id,$table,$params);
        $stmt->execute();
        $r=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $r;
    }

    public function update(string|int $id,string $table,array $dados,string|int $params='id') : int 
    {   
        $stmt  = $this->sql->update($id,$table,$dados,$params);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            if ($e->getCode() == '23505') {
                throw new \PDOException("Falha ao atualizar dados! O campo de email ou o ID de login pode já estar em uso.");
              }
        throw new \PDOException("Falha ao atualizar dados do usuario");
            }
    }
    


}