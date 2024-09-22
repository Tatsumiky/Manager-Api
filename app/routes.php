<?php

declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\RegisterAction\RegisterAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\LoginAction\LoginSessionAction;
use App\Application\Actions\FavoritesAction\SaveFavoriteAction;


return function (App $app) {
    // CORS Pre-Flight OPTIONS Request Handler
    $app->options('/{routes:.*}', function (Request $request, Response $response): Response {
        global $env;
        return $response
            ->withHeader('Access-Control-Allow-Origin', $env['access_origin'])
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    $app->post('/register', RegisterAction::class);
    $app->post("/login", LoginSessionAction::class);
    $app->post('/favorites', SaveFavoriteAction::class);
};
