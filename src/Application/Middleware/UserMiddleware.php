<?php
namespace App\Application\Middleware;


use Slim\Psr7\Response;
use App\Domain\User\User;
use App\classes\CreateLogger;
use App\Application\token\Token;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class UserMiddleware
{
    /**
     * UserMiddleware - Verifica se um usuario tem o nivel de permissão necessario para acessar determinada página.
     *
     * @param  Request $request
     * @param  RequestHandler $handler
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        global $env;
        $response = new Response();
        $nivel = USR_LEVEL ;
        // $primarykey = $_POST['id'] == '' ? null : $_POST['id'];

        if ($nivel === 5) {
           
            return $handler->handle($request);
        }

        setcookie("Authorization",'',-1,'/');
        $response->getBody()->write('Acesso nao permitido. area permitida somente para usuarios ');
        return $response->withStatus(403)
        ;
        // ->withHeader( 
        //     'Set-Cookie', "Authorization=''; Max-Age=-1; Path=/; HttpOnly; Secure; SameSite=Strict");
    }
}