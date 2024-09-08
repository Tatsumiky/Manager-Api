<?php 
namespace App\Application\Actions\LoginAction\LogarAction;


use App\classes\Helpers;
use Psr\Http\Message\ResponseInterface;
use App\Infrastructure\Repository\LoginRepository\LoginRepository;

class LoginSessionAction extends LoginAction {
    use Helpers;

    public function action(): ResponseInterface {
        global $env ;
        $request = $this->request->getParsedBody();
        $body = $this->antiXSS->xss_clean($request);

        if (empty($body['login']) || empty($body['senha'])) {
            $msg = ['summary' => 'Login ou Senha inválidos, tente novamente!'];
            return $this->respondWithData($msg);
        }
        try {
            $r = new LoginRepository($this->sqlInterface);
            $loginResult = $r->login(strtoupper($body['login']), $body['senha']);

            if (isset($loginResult['error'])) {
                return $this->respondWithData($loginResult['error'],401);
            }
            $msg = ['summary' => 'Logado com sucesso!','token'=>$loginResult[1]];
     

            return $this->respondWithData($msg)
            // ->withHeader(
            //     'Set-Cookie',
            //     'Authorization='.$loginResult[1]. '; ' .
            //     'Max-Age=' .$env['exp_token'] . '; ' .
            //     'Path=' .   $env['path'] . '; ' .
            //     'HttpOnly='.$env['httponly'] . '; ' .
            //     'Secure=' . $env['secure'] . '; ' .
            //     "SameSite=".$env['SameSite'].";"
            // )
            ;
            // ->withHeader('Set-Cookie',"Authorization=$loginResult[1]; Max-Age=".$env['exp_token']."; Path=/; HttpOnly=false; Secure=false; SameSite=None");

        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }
}
