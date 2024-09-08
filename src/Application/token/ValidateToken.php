<?php 
namespace App\Application\token;
use App\Application\Action\Action;
use Psr\Http\Message\ResponseInterface;


class ValidateToken extends Action
{
    public function action(): ResponseInterface
    {   
        
       return $this->respondWithData();
    }
}