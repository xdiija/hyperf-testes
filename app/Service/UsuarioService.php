<?php

declare(strict_types=1);
namespace App\Service;

use App\Repository\UsuarioRepository;
class UsuarioService
{
    private UsuarioRepository $usuarioRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
    ) {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function findOrCreateFromGoogle(array $googleUser)
    {
        $user = $this->usuarioRepository->findByEmail($googleUser['email']);
        
        if (!$user) {
            $user = $this->usuarioRepository->create($googleUser);
        }
        
        return $user;
    }

    public function getById(int $idUsuario)
    {
        return $this->usuarioRepository->findById($idUsuario);
    }
}