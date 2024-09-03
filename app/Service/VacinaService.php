<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\VacinaRepository;

class VacinaService
{
    private VacinaRepository $vacinaRepository;
    private PessoaService $pessoaService;

    public function __construct(
        VacinaRepository $vacinaRepository,
        PessoaService $pessoaService
    ) {
        $this->vacinaRepository = $vacinaRepository;
        $this->pessoaService = $pessoaService;
    }
    public function getVacinas(int $idPessoa)
    {   
        $pessoa = $this->pessoaService->getPessoa($idPessoa);
        return [
            'id_pessoa'   => $idPessoa,
            'nome'        => $pessoa['nome'],
            'nome_social' => $pessoa['nome_social'],
            'email'       => $pessoa['email'],
            'telefone'    => $pessoa['telefone'],
            'endereco'    => $pessoa['endereco'],
            'vacinas'     => $this->vacinaRepository->getVacinas($idPessoa)
        ];
    }
}
