<?php
namespace App\classes;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

trait Helpers{
    public function dd($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        die();
    }
    public function safeCall(callable $func, string $errorMessage = 'Um erro ocorreu, por favor tente novamente mais tarde'): array
    {
        try {
            return $func();
        } catch (\Exception $e) {
            return [
                'summary' => $errorMessage,
                'cod' => 500
            ];
        }
    }

    public function post(Request $request): array
	{
		if(!$form = $request->getParsedBody())
			throw new \Exception('Nenhum valor informado!');
       
            foreach ($form as $key => $value) {
                if ($value === null || !preg_match(Regex::NUMERO_INT,$value)) {
                    throw new \Exception("Por favor, preencha todos os campos corretamente.");
                    // return 
                }
            } 
		// return $this->antixss($form, array_keys($form));
       return array_map(fn($e)=>$this->antiXSS->xss_clean($e),$form);
	}

    public function get(Request $request): array 
    {
        if(!$dados = $request->getQueryParams())
            throw new \Exception("Nenhum valor informado nos parametros");

            foreach ($dados as $key => $value) {
                if ($value === null || $value ==='') {
                    throw new \Exception("Por favor, preencha todos os campos corretamente.");
                   
                }
            }   
            return array_map(fn($e)=>$this->antiXSS->xss_clean($e),$dados);

    }
  

    public function verifyDayOfWeek() 
    {
        // $date = $GLOBALS['DAY']->format('D');
        // //Mon 
        // switch ($date) {
        //     case 'Mon':
        //         return [$GLOBALS['date'],1]; //2024-09-02 SIGNIFICA QUE É //SEGUNDA-FEIRA A PERMISSAO É INSERIR E EDITAR      
        //     case 'Tue':
        //         return [$GLOBALS['DAY']->modify('-1 day')->format('Y-m-d'),1];//2024-09-03; //TRAS OS DADOS DE SEGUNDA A PERMISSAO É INSERIR E EDITAR
        //     case 'Wed':
        //         return [$GLOBALS['DAY']->modify('-2 day')->format('Y-m-d'),0];//2024-09-04;  //TRAS OS DADOS DE SEGUNDA A PERMISSAO É APENAS VISUALIZAR
        //     case 'Thu':
        //         return [$GLOBALS['DAY']->modify('-3 day')->format('Y-m-d'),0];//2024-09-05; //TRAS OS DADOS DE SEGUNDA A PERMISSAO É APENAS VISUALIZAR
        //     case 'Fri':
        //         return [$GLOBALS['DAY']->modify('-4 day')->format('Y-m-d'),0];//2024-09-06; //TRAS OS DADOS DE SEGUNDA A PERMISSAO É APENAS VISUALIZAR
        //     case 'Sat':
        //         return [$GLOBALS['DAY']->modify('-5 day')->format('Y-m-d'),0];//2024-09-07; //TRAS OS DADOS DE SEGUNDA A PERMISSAO É APENAS VISUALIZAR
        //     case 'Sun':
        //         return [$GLOBALS['DAY']->modify('-5 day')->format('Y-m-d'),0];//2024-09-03; //TRAS OS DADOS DE SEGUNDA A PERMISSAO É APENAS VISUALIZAR
        //     default:
        //         return false;
        //       //CASO NAO TENHA NENHUM DADO REGISTRADO , A PERMISSAO RETORNA INSERIR
        // }
        return [$GLOBALS['date']];
    }

    public function verifyDate($date) {
        if ($date === null) {
            throw new \Exception("Data inválida");
        }
    
        [$year, $month, $day] = explode('-', $date);

        $check = match (true) {
            !preg_match(Regex::DATE, $date) => 'Formato de data inválido',
            checkdate($month, $day, $year) === false => 'Data inválida, favor verificar',
            date("Y") < $year => 'O Ano selecionado não pode ser maior que o ano atual',
            default => null
        };

        if ($check) {
            throw new \Exception($check);
        }

        return $date;
    }
    
    
}