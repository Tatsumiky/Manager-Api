<?php 
declare(strict_types=1); 
namespace App\Application\Actions\LoginAction\LogarAction;


use voku\helper\AntiXSS;
use App\classes\CreateLogger;
use App\Application\Actions\Action;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;
use App\Infrastructure\Repository\CadastroLeitoRepository\CadastroLeitoRepository;


// use App\Infrastructure\Connection\RedisConn;



abstract class LoginAction extends Action
{
    public function __construct(
        protected SqlInterface $sqlInterface,
        protected AntiXSS $antiXSS ,
        )
    {
        // parent::__construct($logger);
       
    }
}