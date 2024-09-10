<?php
namespace App\Application\Middleware;



use App\classes\CreateLogger;
use Slim\Psr7\Response;
use App\Domain\User\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AdminMiddleware
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
        $response = new Response;
        $nivel = USR_LEVEL ?? null;

        if ($nivel === 1) {

            return $handler->handle($request);
        }

        setcookie("Authorization",'',-1,'/');
        $response->getBody()->write('Acesso não permitido. Area de admnistradores');
        return $response->withStatus(403);
    }  
}