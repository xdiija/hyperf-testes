<?php

namespace App\Helper;

use App\Domain\Entity\Vacina;
use Carbon\Carbon;

class VacinaSerializer
{
    public static function serialize(array $vacinas): string
    {
        $returnData = [];
        foreach ($vacinas as $vacina) {
            foreach ($vacina as $key => $value) {
                $serializeData[$key] = $value;
            }
            $returnData[] = $serializeData;
        }
        return json_encode($returnData);
    }

    public function unserialize(array $data): Vacina
    {  
        $vacina = new Vacina();
        return $vacina
                    ->setId($data['id'])
                    ->setNomeVacina($data['nome_vacina'])
                    ->setDataAprazamento($data['data_aprazamento'])
                    ->setDesativado($data['desativado'])
                    ->setVacinaAtrasada();  
    }
}