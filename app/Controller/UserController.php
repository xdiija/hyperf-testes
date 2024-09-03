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

use App\Service\UserPostService;
use App\Service\UserService;
use Hyperf\HttpServer\Contract\RequestInterface;
use App\Trait\Language;

class UserController extends AbstractController
{

    use Language;

    /**
     * @var UserPostService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
       $this->userService = $userService;
    }

}
