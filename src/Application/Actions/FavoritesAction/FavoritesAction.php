<?php 
declare(strict_types=1); 
namespace App\Application\Actions\FavoritesAction;


use voku\helper\AntiXSS;
use App\Application\Actions\Action;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;


abstract class FavoritesAction extends Action
{
    public function __construct(
        protected SqlInterface $sqlInterface,
        protected AntiXSS $antiXSS ,
        )
    {    }
}