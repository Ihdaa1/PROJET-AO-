<?php
namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;
    public function __construct(string $jwtSecret) { $this->secret = $jwtSecret; }

    public function generate(array $payload, int $ttl = 3600): string
    {
        $now = time();
        $payload = array_merge($payload, ['iat'=>$now, 'exp'=>$now+$ttl]);
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validate(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
