<?php
namespace App\Application\token;

use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\classes\Helpers;
use App\classes\CreateLogger;
use Firebase\JWT\ExpiredException;
use Psr\Container\ContainerInterface;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ServerRequestInterface as request;

class Token
{   
    use Helpers;
    protected Request $request;

    protected static ?ContainerInterface $container = null;  
    private function __construct
    (

        )
    {
      
        
    }
    /**
     * Cria um Token de usuario 
     * @param $email Adiciona email do usuario ao payload
     * @param string $time Adiciona o tempo limite para expirar o token 
     */
    public function generateToken($user)
    {         
        global $env;
        $time =  $env['exp_token'] ?? '60 minutes';
        $key = $env['secretkey'];
        
        $datenow = new DateTime('now', $GLOBALS['TZ']); 
        $datenow->add(date_interval_create_from_date_string($time)); // adc a hora atual o tempo em string "10 minutes"

        $payload = [
            'exp' => $datenow->getTimestamp(),
            'usr_id' => $user['id'],
            'usr_name' => $user['usr_name'],
            'usr_level' => $user['usr_level'],
        ];  
        try {
            $this->TokenConst($payload);
            $jwt = JWT::encode($payload, $key, 'HS256');
        } catch (\Throwable $th) {
            throw new Exception("Não foi possivel gerar token");
        }   
        //gerando constant com dados do usuario 
        // setcookie("Authorization", $jwt, COOKIE_OPTIONS);
        return $jwt;
    }

    public function decodedToken($cookie)
    {   
        global $env;
        $key = $env['secretkey'];
        // $cookie = $_COOKIE["Authorization"] ?? null ;

        if ($cookie == null || $cookie == ''){
            throw new Exception("Cookie/Token Inexistente ou Invalido");
        };

        try {
            $decoded = JWT::decode($cookie, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;      
            $this->tokenConst($decoded_array);

        } catch (ExpiredException $ex) { // Captura um token expirado
            throw new Exception("Acesso nao permitido: Cookie/Token Expirado", 403);

        } catch (SignatureInvalidException $ex) { // Captura um token com assinatura inválida

            throw new Exception("Acesso nao permitido: Cookie/Token Invalido", 403);

        } catch (\Throwable $th) { 
            throw new Exception("Erro ao processar o Token", 500);
        }
 
        return $decoded_array;
    }
    public function tokenConst(array $payload)
    {   
       try {
           define ('USR_ID',$payload['id']);
           define ('USR_NAME',$payload['usr_name']);
           define ('USR_LEVEL',$payload['id_level']);    
       } catch (\Throwable $th) {
            throw new Exception('Erroo ao criar constantes de sessao do usuario');
       }
    }

    public function destructHeaderToken ()
    {   
        
        global $env;
        $this->request->withHeader(
            'Set-Cookie',
            'Authorization=' .''. '; ' .
            'Max-Age=' .-1 . '; ' .
            'Path=' .$env['path'].'; '
        );
    }

    public static function setContainer(ContainerInterface $container): void
    {
        static::$container = $container;
    }

    public static function create(): Token
    {
        return new self(

        );
    }
}
