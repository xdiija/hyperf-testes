<?php 

declare(strict_types=1);

return [
    'client_id' => Hyperf\Support\env('GOOGLE_CLIENT_ID', ''),
    'client_secret' => Hyperf\Support\env('GOOGLE_CLIENT_SECRET', ''),
    'redirect_uri' => Hyperf\Support\env('GOOGLE_REDIRECT_URI', ''),
];
