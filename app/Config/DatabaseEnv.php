<?php

namespace Config;

/**
 * Helper for reading database-related env vars from multiple possible keys.
 * Kept separate from Database.php so we never override BaseConfig methods.
 */
class DatabaseEnv
{
    /**
     * Get first non-empty value from $_ENV, $_SERVER, or getenv() for given keys.
     *
     * @param list<string> $keys
     * @return string|int|float|bool|null
     */
    public static function resolveKeys(array $keys)
    {
        foreach ($keys as $key) {
            $v = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
            if ($v !== false && $v !== null && $v !== '') {
                if (is_string($v) && in_array(strtolower($v), ['true', 'false', 'empty', 'null'], true)) {
                    return match (strtolower($v)) {
                        'true' => true,
                        'false' => false,
                        'empty' => '',
                        'null' => null,
                        default => $v,
                    };
                }
                return $v;
            }
        }
        return null;
    }

    /**
     * Parse MySQL URI (mysql://user:pass@host:port/dbname) into components.
     *
     * @return array{host: string, port: int, user: string, pass: string, db: string}|null
     */
    public static function parseUri(string $uri): ?array
    {
        $uri = trim($uri);
        if (strpos($uri, 'mysql://') !== 0 && strpos($uri, 'mysql:') !== 0) {
            return null;
        }
        $parsed = parse_url(preg_replace('#^mysql:#', 'mysql://', $uri));
        if ($parsed === false || empty($parsed['host'])) {
            return null;
        }
        return [
            'host' => $parsed['host'],
            'port' => (int) ($parsed['port'] ?? 3306),
            'user' => urldecode($parsed['user'] ?? ''),
            'pass' => urldecode($parsed['pass'] ?? ''),
            'db'   => trim(urldecode($parsed['path'] ?? ''), '/') ?: 'defaultdb',
        ];
    }
}
