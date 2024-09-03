<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\VacinaService;

class VacinaController extends AbstractController
{
    private VacinaService $vacinaService;
    public function __construct(VacinaService $vacinaService)
    {
        $this->vacinaService = $vacinaService;
    }

    public function getVacinas(int $idPessoa)
    {   
        if($idPessoa == ""){
            return [
                'code' => 'MISSING_PARAMS',
                'message' => 'O campo idPessoa é obrigatório'
            ];
        }
        return $this->vacinaService->getVacinas($idPessoa);
    }
}
