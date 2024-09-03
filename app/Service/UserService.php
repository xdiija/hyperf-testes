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

use App\Repository\UserRepository;
use DateTime;
use Hyperf\Coroutine\Parallel;
use Hyperf\Contract\StdoutLoggerInterface;
use App\Helper\RedisDriver;

class UserService
{
    private UserRepository $userRepository;
    private RedisDriver $redis;
    private StdoutLoggerInterface $logger;

    private bool $verbose = false;

    public function __construct(
        UserRepository $userRepository,
        RedisDriver $redis,
        StdoutLoggerInterface $logger
    ) {
        $this->userRepository = $userRepository;
        $this->redis = $redis;
        $this->logger = $logger;
    }

    public function findUserSettings($userId): array
    {

        $settings = $this->userRepository->getUserSettings($userId);
        if (null == $settings) {
            if ($this->verbose)
                $this->logger->debug("User [" . $userId .  '] nas no public settings');
            return [];
        }


        $settingsAuthor = array();
        foreach ($settings as $setting) {
            $newSetting = array();
            if ($setting['visibility'] != 'private') {
                $newSetting['id'] = $setting['id'];
                $newSetting['key'] = $setting['key'];
                $newSetting['visibility'] = $setting['visibility'];
                $newSetting['value'] = $setting["value"] != null ? json_decode('["' . trim($setting["value"], "\"") . '"]')[0] : "";
                $settingsAuthor[] = array_filter($newSetting);
            }
        }
        return array_filter($settingsAuthor);
    }

}
