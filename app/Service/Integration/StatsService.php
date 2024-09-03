<?php

namespace App\Service\Integration;

use App\Exception\BadException;
use App\Helper\StringHelper;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Config\ConfigFactory;
use Hyperf\Context\ApplicationContext;

class StatsService
{
    private HttpClient $httpClient;
    public function __construct()
    {
        $config = new ConfigFactory;
        $config = $config(ApplicationContext::getContainer());

        $url = $config->get('integrations.stats_api');
        $url = rtrim($url, '/\\');

        $this->httpClient = new HttpClient([
            'base_uri' => sprintf('%s', $url),
        ]);
    }


    /**
     * @throws GuzzleException
     * @throws BadException
     */
    public function getPostStats(int $userId, int $postId): array
    {
        try {
            $res = $this->httpClient->get(
                sprintf('/v1/author/%s/content/%s', $userId, $postId)
            );

            if ($res->getStatusCode() === 200) {
                return json_decode($res->getBody()->getContents(), true);
            }
        } catch (\Exception $e) {
            throw new BadException(
                StringHelper::getJson($e->getMessage()) ?? $e->getMessage()
            );
        }

        return [];
    }

    /**
     * @throws GuzzleException
     * @throws BadException
     */
    public function getMultiPostStats(int $userId, array $postsId): array
    {
        try {
            $reqBody = ['contents' => $postsId];
            $res = $this->httpClient->post(
                sprintf('/v1/author/%s/multi-content', $userId),
                [
                    'json' => $reqBody
                ]
            );

            if ($res->getStatusCode() !== 200) {
                return [];
            }
            $resBody = json_decode($res->getBody()->getContents(), true);
            $response = [];
            if (is_array($resBody)) {
                foreach ($resBody as $content) {
                    $response[$content['content_id']] = [
                        'stats' => $content['stats'],
                        'stats_reactions' => $content['stats_reactions'],
                    ];
                }
            }
            return $response;
        } catch (\Exception $e) {
            throw new BadException(
                StringHelper::getJson($e->getMessage()) ?? $e->getMessage()
            );
        }
    }
}