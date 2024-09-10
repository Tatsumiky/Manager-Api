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
        $date = $GLOBALS['DAY']->format('D');
    
    return match ($date) {
        'Mon' => [$GLOBALS['date'], 'Mon'],
        //'Tue' => [$GLOBALS['date'], 'Tue'],
        'Tue' => [$GLOBALS['DAY']->modify('-1 day')->format('Y-m-d'), 'Tue'],
        'Wed' => [$GLOBALS['DAY']->modify('-2 day')->format('Y-m-d'), 'Wed'],
        'Thu' => [$GLOBALS['DAY']->modify('-3 day')->format('Y-m-d'), 'Thu'],
        'Fri' => [$GLOBALS['DAY']->modify('-4 day')->format('Y-m-d'), 'Fri'],
        'Sat' => [$GLOBALS['DAY']->modify('-5 day')->format('Y-m-d'), 'Sat'],
        'Sun' => [$GLOBALS['DAY']->modify('-5 day')->format('Y-m-d'), 'Sun'],
        default => throw new \Exception('Dia da semana não tratado')
    };
        // return [$GLOBALS['date']];
    }

    public function verifyMondayOrTuesday($date) {
        $this->verifyDate($date);
    
        // 1 = Segunda-feira,2 = Terça-feira, 3 = Quarta-feira, 4 = Quinta-feira, 5 = Sexta-feira, 6 = Sábado, 7 = Domingo

        $dayOfWeek = date('N', strtotime($date));
    
        if (!$dayOfWeek == 1 || !$dayOfWeek == 2) {
            throw new \Exception("A data não pode ser diferente de segunda ou terça-feira.");
        }
    
        return $date; 
    }

    public function verifyDate($date) {
        $check = match (true) {
            $date === null => 'Data inválida',
            !preg_match(Regex::DATE, $date) => 'Formato de data inválido',
            $date > $GLOBALS['date']=> 'Data maior que o dia atual, favor verificar',
            default => null
        };

        if ($check) {
            throw new \Exception($check);
        }

        return $date;
    }
    
    
}