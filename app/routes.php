<?php

declare(strict_types=1);

use Slim\App;
use App\Application\Actions\Series\SeriesAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Actions\RegisterAction\RegisterAction;
use App\Application\Actions\SeriesAction\ListSeriesAction;
use App\Application\Actions\SeriesAction\SaveSeriesAction;
use App\Application\Actions\SeriesAction\ViewSeriesAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\LoginAction\LoginSessionAction;
use App\Application\Actions\SeriesAction\DeleteSeriesAction;
use App\Application\Actions\SeriesAction\UpdateSeriesAction;
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


    $app->group('/series', function () use ($app) {
        $app->post("save", SaveSeriesAction::class); // Para criar uma nova série
        $app->get("all", ListSeriesAction::class); // Para listar todas as séries
        $app->get("/{id}", ViewSeriesAction::class); // Para visualizar uma série específica
        $app->post("/{id}/update", UpdateSeriesAction::class); // Para atualizar uma série
        $app->post("/{id}/delete", DeleteSeriesAction::class); // Para deletar uma série
    });
    
    
    

};
