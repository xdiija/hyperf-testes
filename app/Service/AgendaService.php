<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Service;

use App\Repository\AgendaRepository;

class AgendaService
{
    private AgendaRepository $agendaRepository;

		private MunicipioService $municipioService;

		private UnidadeService $unidadeService;

    public function __construct(
			AgendaRepository $agendaRepository,
			MunicipioService $municipioService,
			UnidadeService $unidadeService,
		)
		{
      $this->agendaRepository = $agendaRepository;
			$this->municipioService = $municipioService;
			$this->unidadeService = $unidadeService;
    }

    public function getAgenda(int $matricula, $requestValues)
    {
        $dataAgenda = $requestValues['dataAgenda'];
				$dataAgenda = $dataAgenda == '' ? date('Y-m-d H:i:s'): $dataAgenda;
				$agenda = $this->agendaRepository->getAgenda($matricula, $dataAgenda);
				if($agenda == null) {
					return [
						'message' => 'Nenhuma agenda encontrada!'
					];
				}
				$municipio = $this->municipioService->getMunicipio($agenda['id_municipio']);
				$unidade = $this->unidadeService->getUnidadeById($agenda['id_unidade']);
				$agenda['municipio'] = $municipio;
				$agenda['unidade'] = $unidade;

        return $agenda;
    }
}