<?php declare(strict_types=1);

namespace App\Domain\Entity;

class PessoaApp
{
    public int $idUsuario;
    public int $idUsuarioSus;
    public int $idPessoa;
    public string $nome;
    public ?string $matricula;
    public function setIdUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdPessoa(int $idPessoa)
    {
        $this->idPessoa = $idPessoa;
        return $this;
    }

    public function getIdPessoa()
    {
        return $this->idPessoa;
    }

    public function setIdUsuarioSus(int $idUsuarioSus)
    {
        $this->idUsuarioSus = $idUsuarioSus;
        return $this;
    }

    public function getIdUsuarioSus()
    {
        return $this->idUsuarioSus;
    }

    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setMatricula(?string $matricula = "")
    {
        $this->matricula = $matricula;
        return $this;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public static function setFromPessoa(PessoaApp $pessoa): self
    {
        $pessoaInterface = new self();
        $pessoaInterface->setIdUsuario($pessoa->getIdUsuario());
        $pessoaInterface->setNome($pessoa->getNome());
        $pessoaInterface->setIdPessoa($pessoa->getIdPessoa());
        $pessoaInterface->setMatricula($pessoa->getMatricula());
        $pessoaInterface->setIdUsuarioSus($pessoa->getIdUsuarioSus());
        return $pessoaInterface;
    }

}