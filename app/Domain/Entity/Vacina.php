<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Carbon\Carbon;

class Vacina
{
    private int $id;
    private string $nomeVacina;
    private string $dataAprazamento;
    private bool $desativado;
    private bool $vacinaAtrasada;
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setNomeVacina(string $nomeVacina)
    {
        $this->nomeVacina = $nomeVacina;
        return $this;
    }
    public function getNomeVacina()
    {
        return $this->nomeVacina;
    }
    public function setDataAprazamento(string $dataAprazamento)
    {
        $this->dataAprazamento = $dataAprazamento;
        return $this;
    }
    public function getDataAprazamento()
    {
        return $this->dataAprazamento;
    }
    public function setDesativado(bool $desativado)
    {
        $this->desativado = $desativado;
        return $this;
    }
    public function getDesativado()
    {
        return $this->desativado;
    }
    public function getVacinaAtrasada()
    {
        return $this->vacinaAtrasada;
    }
    public function setVacinaAtrasada()
    {
        $now = Carbon::now();
        $this->vacinaAtrasada = $now->greaterThan($this->dataAprazamento) && !$this->desativado; 
        return $this;
    }
}