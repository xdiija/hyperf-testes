<?php

declare(strict_types=1);

namespace App\Service;

use Google\Client;
use Google\Service\Oauth2;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class GoogleAuthService
{
    private Client $client;
    
    public function __construct(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $this->client = new Client();
        $this->client->setClientId($config->get('google.client_id'));
        $this->client->setClientSecret($config->get('google.client_secret'));
        $this->client->setRedirectUri($config->get('google.redirect_uri'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function getUserInfo(string $code): array
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($token['error'])) {
            throw new \RuntimeException($token['error_description'] ?? 'Google authentication failed');
        }
        
        $this->client->setAccessToken($token);
        
        $oauth2 = new Oauth2($this->client);
        $userInfo = $oauth2->userinfo->get();
        
        return [
            'google_id' => $userInfo->getId(),
            'email' => $userInfo->getEmail(),
            'name' => $userInfo->getName(),
            'avatar_url' => $userInfo->getPicture(),
        ];
    }
}