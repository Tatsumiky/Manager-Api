<?php
namespace App\Application\Actions\RegisterAction;

use App\classes\Helpers;
use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\LoginAction\LoginAction;
use App\Infrastructure\Repository\LoginRepository\LoginRepository;

class RegisterAction extends LoginAction {
    use Helpers;

    public function action(): ResponseInterface {
        $request = $this->request->getParsedBody();
        $body = $this->antiXSS->xss_clean($request);

        if (empty($body['login']) || empty($body['senha']) || empty($body['email'])) {
            $msg = ['summary' => 'Login, senha ou email não fornecidos.'];
            return $this->respondWithData($msg, 400);
        }

        $hashedPassword = password_hash($body['senha'], PASSWORD_BCRYPT);

        $userData = [
            'login' => strtoupper($body['login']),
            'senha' => $hashedPassword,
            'email' => $body['email'],
        ];

        try {
            $r = new LoginRepository($this->sqlInterface);
            $userId = $r->registerUser($userData);

            if (isset($userId['error'])) {
                return $this->respondWithData($userId['error'], 400);
            }

            $msg = ['summary' => 'Usuário registrado com sucesso!', 'user_id' => $userId];
            return $this->respondWithData($msg, 201);

        } catch (\Throwable $th) {
            $msg = ['summary' => 'Erro ao registrar usuário', 'error' => $th->getMessage()];
            return $this->respondWithData($msg, 500);
        }
    }
}
