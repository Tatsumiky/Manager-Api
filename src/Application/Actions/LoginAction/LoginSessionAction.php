<?php 
namespace App\Application\Actions\LoginAction;

use App\classes\Helpers;
use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\LoginAction\LoginAction;
use App\Infrastructure\Repository\LoginRepository\LoginRepository;

class LoginSessionAction extends LoginAction {
    use Helpers;

    public function action(): ResponseInterface {
        global $env;
        $request = $this->request->getParsedBody();
        $body = $this->antiXSS->xss_clean($request);

        if (empty($body['login']) || empty($body['senha'])) {
            $msg = ['summary' => 'Login ou Senha inválidos, tente novamente!'];
            return $this->respondWithData($msg, 400);
        }

        try {
            $r = new LoginRepository($this->sqlInterface);
            $loginResult = $r->login(strtoupper($body['login']), $body['senha']);

            if (isset($loginResult['error'])) {
                return $this->respondWithData($loginResult['error'], 401);
            }
            $msg = [
                'summary' => 'Logado com sucesso!',
                'token' => $loginResult[1],
                'user' => $loginResult[0],
            ];

            return $this->respondWithData($msg, 200);

        } catch (\Throwable $th) {
            $msg = ['summary' => 'Erro ao realizar login', 'error' => $th->getMessage()];
            return $this->respondWithData($msg, 500);
        }
    }
}
