<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Helper\Database;

final class AgendaRepository
{
	private Database $db;

	public function __construct(Database $database)
	{
		$this->db = $database;
	}

	public function getAgenda(int $matricula, $dataAgenda)
	{
		$sql = "SELECT
							id_municipio, id_agenda, matricula, situacao_agenda_app, agenda_dth, tipo_agenda, id_unidade, tipo_procedimento,
							procedimento, especialidade, nome_profissional, especialidade_profissional, descricao, solicitacao_dth, situacao,
							comparecimento, id_municipio_destino, destino, subprestador, embarque_dth, local_embarque, desativado, atualizacao_dth,
							criacao_dth, id_tipo_agenda_sub
						FROM public.agenda
						WHERE matricula = $matricula
							AND agenda_dth::DATE = '$dataAgenda'::DATE";
		$result = $this->db->run($sql);
		return !empty($result) ? $result[0] : null;
	}
}