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

namespace App\Controller;

use App\Service\DependentesService;

class DependentesController extends AbstractController
{
    private DependentesService $dependentesService;

    public function __construct(DependentesService $dependentesService)
    {
        $this->dependentesService = $dependentesService;
    }

    public function getDependentes(int $idUsuarioApp = 0)
    {
        if ($idUsuarioApp == 0) {
            return 'Id UsuarioApp inválido';
        }
        return $this->dependentesService->getDependentes($idUsuarioApp);
    }
}
