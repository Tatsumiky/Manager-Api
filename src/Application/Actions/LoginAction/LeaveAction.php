<?php 
namespace App\Application\Actions\LoginAction\LogarAction;

use App\Application\Actions\LoginAction\LoginAction;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use App\classes\Helpers;
use App\Domain\User\User;
use App\Application\Action\UteisAction;


class LeaveAction extends LoginAction
{     
    use Helpers;
    /**
     * Utilizada na hora do Logout do usuario
     *
     * @return Response
     */
    protected function action(): Response 
    {   
        
        global $env;
       
        $response= ['summary'=>'Sessao encerrada com sucesso'];
       
        return $this->respondWithData($response)->withHeader( 
      'Set-Cookie', "Authorization=; Max-Age=0; Path=/;");
    }
    



}




