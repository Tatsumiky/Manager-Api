<?php
namespace App\Application\Middleware;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Slim\Psr7\Response;
use App\classes\Helpers;
use Firebase\JWT\ExpiredException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Application\token\Token;

class TokenMiddleware implements Middleware {
    use Helpers;

    public function process(Request $request, RequestHandler $handler): Response {
        $tokenService = Token::create();
        $response = new Response();

        $authHeader = $request->getHeader("Authorization");
        $token = $authHeader[0] ?? null;

        if (!$token) {
            $response->getBody()->write('Acesso não permitido: Token inexistente.');
            return $response->withStatus(403);
        }

        try {
            // Decodificar token
            $dados = $tokenService->decodedToken($token);
        } catch (ExpiredException $e) {
            setcookie("Authorization", '', -1, '/');
            $response->getBody()->write('Acesso não permitido: Token expirado.');
            return $response->withStatus(403);
        }

        return $handler->handle($request);
    }
}
