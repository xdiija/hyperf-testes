<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GoogleAuthService;
use App\Service\UsuarioService;
use App\Helper\Jwt;
use Carbon\Carbon;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class AuthController
{
    public function __construct(
        private GoogleAuthService $googleAuthService,
        private UsuarioService $usuarioService,
        private Jwt $jwt,
    ) {}

    public function redirectToGoogle(ResponseInterface $response)
    {
        $url = $this->googleAuthService->getAuthUrl();
        return $response->redirect($url);
    }

    public function handleGoogleCallback(RequestInterface $request, ResponseInterface $response)
    {
        $code = $request->input('code');
        
        if (!$code) {
            return $response->json([
                'error' => 'Authorization code not found'
            ])->withStatus(400);
        }
        
        try {
            $googleUser = $this->googleAuthService->getUserInfo($code);
            $usuario = $this->usuarioService->findOrCreateFromGoogle($googleUser);
            
            $token = $this->jwt->encode([
                'idUsuario' => $usuario['id'],
                'email' => $usuario['email'],
            ]);
            
            return $response->redirect(
                '/auth/callback?token=' . urlencode($token) . 
                '&user=' . urlencode(json_encode($usuario))
            );
            
        } catch (\Throwable $e) {
            return $response->json([
                'error' => $e->getMessage()
            ])->withStatus(500);
        }
    }

    public function callbackPage(RequestInterface $request, ResponseInterface $response)
    {
        $token = $request->input('token');
        $user = $request->input('user');
        
        if (!$token || !$user) {
            return $response->html('<script>window.close();</script>');
        }
        
        return $response->html(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Authentication Complete</title>
                <script>
                    window.onload = function() {
                        window.opener.postMessage({
                            token: "$token",
                            user: $user
                        }, "http://127.0.0.1:5500"); 
                        window.close();
                    };
                </script>
            </head>
            <body>
                <p>Authentication complete. Please wait...</p>
            </body>
            </html>
            HTML
        );
    }

    public function validateToken(RequestInterface $request, ResponseInterface $response)
    {
        $authHeader = $request->header('Authorization') ?? '';
        $token = preg_replace(
            '/Bearer /i',
            '',
            $authHeader
        );
        
        try {
            $decoded = $this->jwt->validate($token);
            
            // Additional validation for your specific token structure
            if (empty($decoded['idUsuario']) || empty($decoded['email'])) {
                throw new \RuntimeException('Invalid token payload');
            }
            
            // Check expiration if you're not using JWT's built-in exp claim
            if (isset($decoded['validade'])) {
                $expiration = Carbon::parse($decoded['validade']);
                if ($expiration->isPast()) {
                    throw new \RuntimeException('Token has expired');
                }
            }
            
            return $response->json([
                'valid' => true,
                'user' => [
                    'id' => $decoded['idUsuario'],
                    'email' => $decoded['email']
                ]
            ]);
            
        } catch (\RuntimeException $e) {            
            return $response->json([
                'valid' => false,
                'error' => $e->getMessage()
            ])->withStatus(401);
        }
    }
}