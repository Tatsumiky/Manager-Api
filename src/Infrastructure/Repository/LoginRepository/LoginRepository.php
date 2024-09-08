<?php
namespace App\Infrastructure\Repository\LoginRepository;

use App\Application\token\Token;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;

class LoginRepository {
    public function __construct(private SqlRepository $sql) {}

    public function login(int|string $login, string $senha) {
        $sql = ($this->sql->selectUserOfId($login, 'unidades', 'unidades'))[0] ?? null;
        // $pass = password_verify($senha, $sql['senha']);
        $pass =true;
        $v = match(true) {
            (empty($sql['unidades']) || $sql === null) => ['error' => ['summary' => 'Usuario ou Senha invalida']],
            !$pass => ['error' => ['summary' => 'Usuario ou Senha invalida']],
            default => function () use ($sql) {
                $session = $this->create_session($sql);
                if (!$sql || !$session) {
                    return ['error' => ['summary' => 'Não foi possível iniciar a sessão do usuário. Verifique se os cookies estão permitidos.', 'code' => 400]];
                }
                return [$sql,$session]; 
            },
        };
        return is_array($v) ? $v : $v();
    }

    private function create_session($user) {
        
      
     return token::create()->generateToken($user);

    
    }
}