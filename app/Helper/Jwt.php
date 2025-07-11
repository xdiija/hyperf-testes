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
namespace App\Helper;

use Carbon\Carbon;
use Firebase\JWT\Key;
use Firebase\JWT\JWT as FirebaseJWT;
use Hyperf\Config\ConfigFactory;
use Hyperf\Context\ApplicationContext;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use InvalidArgumentException;
use UnexpectedValueException;
use DomainException;
class Jwt
{
    /**
     * @const ALG
     */
    public const ALG = 'HS256';

    private string $jwtSecret;

    public function __construct()
    {
        $config = new ConfigFactory();
        $config = $config(ApplicationContext::getContainer());
        $this->jwtSecret = $config->get('jwt.default.secret');
    }

    public function decode(string $encoded): object
    {
        return FirebaseJWT::decode(
            $encoded,
            new Key(
                $this->jwtSecret,
                self::ALG
            )
        );
    }

    public function encode(array $data): string
    {  
        $data['validade'] = Carbon::now()->addDays(1)->toDateTimeString();
        return FirebaseJWT::encode(
            $data,
            $this->jwtSecret,
            self::ALG
        );
    }

    public function validate(string $token): array
    {
        try {
            $decoded = $this->decode($token);

            if (!isset($decoded->validade) || Carbon::parse($decoded->validade)->isPast()) {
                throw new \RuntimeException('Token has expired');
            }

            return (array) $decoded;
            
        } catch (InvalidArgumentException $e) {
            throw new \RuntimeException('Invalid token provided');
        } catch (DomainException $e) {
            throw new \RuntimeException('Invalid token algorithm');
        } catch (SignatureInvalidException $e) {
            throw new \RuntimeException('Token signature verification failed');
        } catch (BeforeValidException $e) {
            throw new \RuntimeException('Token not yet valid');
        } catch (ExpiredException $e) {
            throw new \RuntimeException('Token has expired');
        } catch (UnexpectedValueException $e) {
            throw new \RuntimeException('Malformed token');
        }
    }
}