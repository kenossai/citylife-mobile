<?php

namespace App\Support;

use Native\Mobile\Facades\SecureStorage;

class MemberSession
{
    private const TOKEN_KEY = 'citylife_member_token';

    public static function token(): ?string
    {
        return SecureStorage::get(self::TOKEN_KEY) ?: session('citylife.member_token');
    }

    /** @return array{id: int, first_name: string, last_name: string, name: string, email: string, phone: ?string, membership_number: ?string, member_since: ?string}|null */
    public static function member(): ?array
    {
        return self::token() ? session('citylife.member') : null;
    }

    public static function check(): bool
    {
        return self::member() !== null;
    }

    /** @return array{ok: bool, message: ?string} */
    public static function login(string $email, string $password): array
    {
        $result = ContentApi::send('POST', 'auth/login', [
            'email' => $email,
            'password' => $password,
            'device_name' => 'CityLife app',
        ]);

        if (! $result['ok']) {
            return ['ok' => false, 'message' => $result['message']];
        }

        self::store($result['data']['token'], $result['data']['member']);

        return ['ok' => true, 'message' => null];
    }

    public static function logout(): void
    {
        if ($token = self::token()) {
            ContentApi::send('POST', 'auth/logout', [], $token);
        }

        self::forget();
    }

    public static function forget(): void
    {
        SecureStorage::set(self::TOKEN_KEY, null);
        session()->forget(['citylife.member_token', 'citylife.member', 'citylife.my_prayer_requests']);
    }

    /**
     * Call a member-only endpoint, signing the member out locally when the website no longer accepts their token.
     *
     * @param  array<string, mixed>  $payload
     * @return array{ok: bool, status: int, data: mixed, message: ?string}
     */
    public static function request(string $method, string $path, array $payload = []): array
    {
        $result = ContentApi::send($method, $path, $payload, self::token());

        if ($result['status'] === 401) {
            self::forget();
        }

        return $result;
    }

    /** @param array<string, mixed> $member */
    private static function store(string $token, array $member): void
    {
        if (! SecureStorage::set(self::TOKEN_KEY, $token)) {
            session()->put('citylife.member_token', $token);
        }

        session()->put('citylife.member', $member);
    }
}
