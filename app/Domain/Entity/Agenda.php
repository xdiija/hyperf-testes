<?php declare(strict_types=1);

namespace App\Domain\Entity;

use DateTime;

class Agenda
{
	public int $idAgenda;
	public int $situacaoAgendaApp;
	public DateTime $agendaDth;
	public int $tipoAgenda;
	public string $tipoProcedimento;
	public string $procedimento;
	public string $descricao;
	public DateTime $solicitacaoDth;
	public int $situcao;
	public DateTime $embarqueDth;
	public string $localEmbarque;
	public bool $desativado;
	public DateTime $atualizacaoDth;
	public DateTime $criacaoDth;
	public int $idTipoAgendaSub;


	public function setIdAgenda(int $idAgenda)
	{
		$this->$idAgenda = $idAgenda;
		return $this;
	}

	public function getIdAgenda()
	{
		return $this->idAgenda;
	}

	public function setSituacaoAgendaApp(int $situacaoAgendaApp)
	{
		$this->$situacaoAgendaApp = $situacaoAgendaApp;
		return $this;
	}

	public function getSituacaoAgendaApp()
	{
		return $this->situacaoAgendaApp;
	}

	public function setAgendaDth(DateTime $agendaDth)
	{
		$this->$agendaDth = $agendaDth;
		return $this;
	}

	public function getAgendaDth()
	{
		return $this->agendaDth;
	}

	public function setTipoAgenda(int $tipoAgenda)
	{
		$this->$tipoAgenda = $tipoAgenda;
		return $this;
	}

	public function getTipoAgenda()
	{
		return $this->tipoAgenda;
	}

	public function setTipoProcedimento(string $tipoProcedimento)
	{
		$this->$tipoProcedimento = $tipoProcedimento;
		return $this;
	}

	public function getTipoProcedimento()
	{
		return $this->tipoProcedimento;
	}

	public function setProcedimento(string $procedimento)
	{
		$this->$procedimento = $procedimento;
		return $this;
	}

	public function getProcedimento()
	{
		return $this->procedimento;
	}

	public function setDescricao(string $descricao)
	{
		$this->$descricao = $descricao;
		return $this;
	}

	public function getDescricao()
	{
		return $this->descricao;
	}

	public function setSolicitacaoDth(DateTime $solicitacaoDth)
	{
		$this->$solicitacaoDth = $solicitacaoDth;
		return $this;
	}

	public function getSolicitacaoDth()
	{
		return $this->solicitacaoDth;
	}

	public function setSitucao(int $situcao)
	{
		$this->$situcao = $situcao;
		return $this;
	}

	public function getSituacao()
	{
		return $this->situcao;
	}

	public function setEmbarqueDth(DateTime $embarqueDth)
	{
		$this->$embarqueDth = $embarqueDth;
		return $this;
	}

	public function getEmbarqueDth()
	{
		return $this->embarqueDth;
	}

	public function setLocalEmbarque(string $localEmbarque)
	{
		$this->$localEmbarque = $localEmbarque;
		return $this;
	}

	public function getLocalEmbarque()
	{
		return $this->localEmbarque;
	}

	public function setDesativado(bool $desativado)
	{
		$this->$desativado = $desativado;
		return $this;
	}

	public function getDesativado()
	{
		return $this->desativado;
	}

	public function setAtualizacaoDth(DateTime $atualizacaoDth)
	{
		$this->$atualizacaoDth = $atualizacaoDth;
		return $this;
	}

	public function getAtualizacaoDth()
	{
		return $this->atualizacaoDth;
	}

	public function setCriacaoDth(DateTime $criacaoDth)
	{
		$this->$criacaoDth = $criacaoDth;
		return $this;
	}

	public function getCriacaoDth()
	{
		return $this->criacaoDth;
	}

	public function setIdTipoAgendaSub(int $idTipoAgendaSub)
	{
		$this->$idTipoAgendaSub = $idTipoAgendaSub;
		return $this;
	}

	public function getIdTipoAgendaSub()
	{
		return $this->idTipoAgendaSub;
	}
}