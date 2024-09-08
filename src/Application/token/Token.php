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
        protected CreateLogger $log,
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
            'id_unidade' => $user['id_unidade'],
            'unidade' => $user['unidades'],
            'cnes' => $user['cnes'],
            'id_adm' => $user['id_adm'],
            'id_nivel' => $user['id_nivel'],
        ];  
        try {
            $this->TokenConst($payload);
            $jwt = JWT::encode($payload, $key, 'HS256');
        } catch (\Throwable $th) {

            $this->log->loggerCSV("Token_generete_fail",'tentativa de gerar token falhou'.$th->getMessage(),'warning',$_SERVER['REMOTE_ADDR']);
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
            $this->log->loggerCSV("token_decoded_fail",'tentativa de acesso com Cookie/Token Inexistente ou Invalido','warning',$_SERVER['REMOTE_ADDR']);
            // $this->destructHeaderToken();
            throw new Exception("Cookiee/Token Inexistente ou Invalido");
        };

        try {
            $decoded = JWT::decode($cookie, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;      
            $this->tokenConst($decoded_array);

        } catch (ExpiredException $ex) { // Captura um token expirado
            // $this->destructHeaderToken();
            $this->log->loggerCSV("token_decoded_fail_", 'tentativa de acesso com Token Expirado', 'warning', $_SERVER['REMOTE_ADDR']);
            throw new Exception("Acesso nao permitido: Cookie/Token Expirado", 403);

        } catch (SignatureInvalidException $ex) { // Captura um token com assinatura inválida
            // $this->destructHeaderToken();
            $this->log->loggerCSV("token_decoded_fail_", 'tentativa de acesso com Token Alterado ou Invalido', 'warning', $_SERVER['REMOTE_ADDR']);
            throw new Exception("Acesso nao permitido: Cookie/Token Invalido", 403);

        } catch (\Throwable $th) { 
            // $this->destructHeaderToken();
            $this->log->loggerCSV("token_decoded_fail", $th->getMessage(), 'warning', $_SERVER['REMOTE_ADDR']);
            throw new Exception("Erro ao processar o Token", 500);
        }
 
        return $decoded_array;
    }
    public function tokenConst(array $payload)
    {   
       try {
           define ('UNI_ID',$payload['id_unidade']);
           define ('UNI_NAME',$payload['unidade']);
           define ('UNI_CNES',$payload['cnes']);
           define ('UNI_ADM',$payload['id_adm']);
           define ('UNI_NIVEL',$payload['id_nivel']);    
       } catch (\Throwable $th) {
            $this->log->loggerCSV('token_constants_fail',$th->getMessage());
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
         static::$container->get(CreateLogger::class),
        
         


        );
    }
}
