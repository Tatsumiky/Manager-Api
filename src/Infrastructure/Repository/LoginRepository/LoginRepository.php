<?php
namespace App\Infrastructure\Repository\LoginRepository;

use App\Application\token\Token;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;

class LoginRepository {
    public function __construct(private SqlRepository $sql) {}

    public function login(int|string $login, string $senha) {
        $sql = $this->sql->selectUserOfId($login, 'users', 'usr_name')[0] ?? null;

        if (!$sql) {
            return ['error' => ['summary' => 'Usuário ou senha inválidos']];
        }

        $pass = password_verify($senha, $sql['password']);
        
        if (!$pass) {
            return ['error' => ['summary' => 'Usuário ou senha inválidos']];
        }
        $session = $this->create_session($sql);
        if (!$session) {
            return ['error' => ['summary' => 'Não foi possível iniciar a sessão do usuário. Verifique se os cookies estão permitidos.']];
        }

        return [$sql, $session]; 
    }

    public function registerUser(array $userData) {
        $existingUser = $this->sql->selectUserOfId($userData['login'], 'users', 'usr_name');
        if ($existingUser) {
            return ['error' => ['summary' => 'Usuário já existe com esse login']];
        }
        $userId = $this->sql->insert('users', [
            'usr_name' => $userData['login'],
            'password' => $userData['senha'],
            'email' => $userData['email'],
        ]);

        if (!$userId) {
            return ['error' => ['summary' => 'Erro ao registrar o usuário']];
        }

        return $userId;
    }

    private function create_session($user) {
        return Token::create()->generateToken($user);
    }
}
