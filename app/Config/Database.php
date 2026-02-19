<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'portfolio_db',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'foundRows'    => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    //    /**
    //     * Sample database connection for SQLite3.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'database'    => 'database.db',
    //        'DBDriver'    => 'SQLite3',
    //        'DBPrefix'    => '',
    //        'DBDebug'     => true,
    //        'swapPre'     => '',
    //        'failover'    => [],
    //        'foreignKeys' => true,
    //        'busyTimeout' => 1000,
    //        'synchronous' => null,
    //        'dateFormat'  => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for Postgre.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'public',
    //        'DBDriver'   => 'Postgre',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'port'       => 5432,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for SQLSRV.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'dbo',
    //        'DBDriver'   => 'SQLSRV',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'encrypt'    => false,
    //        'failover'   => [],
    //        'port'       => 1433,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for OCI8.
    //     *
    //     * You may need the following environment variables:
    //     *   NLS_LANG                = 'AMERICAN_AMERICA.UTF8'
    //     *   NLS_DATE_FORMAT         = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_FORMAT    = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS'
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => 'localhost:1521/XEPDB1',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'DBDriver'   => 'OCI8',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'AL32UTF8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'synchronous' => null,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
            return;
        }

        // Production (e.g. Render): use env vars so credentials are not in repo.
        // Try multiple key names because some hosts normalize or prefix env vars (dots → underscores, etc.).
        $hostname = $this->getEnvValue(['database.default.hostname', 'DATABASE_DEFAULT_HOSTNAME', 'DB_HOST', 'MYSQL_HOST']);
        $username = $this->getEnvValue(['database.default.username', 'DATABASE_DEFAULT_USERNAME', 'DB_USERNAME', 'DB_USER', 'MYSQL_USER']);
        $password = $this->getEnvValue(['database.default.password', 'DATABASE_DEFAULT_PASSWORD', 'DB_PASSWORD', 'MYSQL_PASSWORD']);
        $database = $this->getEnvValue(['database.default.database', 'DATABASE_DEFAULT_DATABASE', 'DB_DATABASE', 'DB_NAME', 'MYSQL_DATABASE']);
        $port     = $this->getEnvValue(['database.default.port', 'DATABASE_DEFAULT_PORT', 'DB_PORT', 'MYSQL_PORT']);

        // Optional: parse full MySQL URI (e.g. Aiven "Service URI") if hostname not set
        $uri = $this->getEnvValue(['MYSQL_URI', 'DATABASE_URL']);
        if (($hostname === null || $hostname === '' || $hostname === 'localhost') && $uri !== null && $uri !== '') {
            $parsed = $this->parseDatabaseUri($uri);
            if ($parsed !== null) {
                $hostname = $parsed['host'];
                $port     = $port ?? $parsed['port'];
                $username = $username ?? $parsed['user'];
                $password = $password ?? $parsed['pass'];
                $database = $database ?? $parsed['db'];
            }
        }

        if ($hostname !== null && $hostname !== '') {
            $this->default['hostname'] = trim((string) $hostname);
        }
        if ($username !== null) {
            $this->default['username'] = $username;
        }
        if ($password !== null) {
            $this->default['password'] = (string) $password;
        }
        if ($database !== null && $database !== '') {
            $this->default['database'] = $database;
        }
        if ($port !== null && $port !== '') {
            $this->default['port'] = (int) $port;
        }

        // Force TCP when host is localhost (avoids "No such file or directory" socket error on Linux)
        if ($this->default['hostname'] === 'localhost') {
            $this->default['hostname'] = '127.0.0.1';
        }

        // In production, if host is still local, env vars are not being read — fail with a clear message
        if (ENVIRONMENT === 'production' && in_array($this->default['hostname'], ['127.0.0.1', 'localhost'], true)) {
            throw new \CodeIgniter\Database\Exceptions\DatabaseException(
                'Database host is still localhost/127.0.0.1. Render is not passing env vars to the app. '
                . 'In Render → Environment, add DB_HOST = your Aiven host (e.g. portfolio1-db-portfoliomine.d.aivencloud.com), '
                . 'plus DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD. Then Save and redeploy. '
                . 'Keys tried: database.default.hostname, DATABASE_DEFAULT_HOSTNAME, DB_HOST, MYSQL_HOST.'
            );
        }

        // Aiven (and similar) require SSL. If CA cert content is provided via env, write to file and enable encrypt.
        $sslCaContent = $this->getEnvValue(['database.default.encrypt.ssl_ca_content', 'DATABASE_DEFAULT_ENCRYPT_SSL_CA_CONTENT', 'DB_SSL_CA_CONTENT']);
        if ($this->default['hostname'] !== '' && $sslCaContent !== null && trim((string) $sslCaContent) !== '') {
            $cacheDir = defined('WRITEPATH') ? WRITEPATH . 'cache' : (FCPATH . '..' . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR . 'cache');
            if (! is_dir($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }
            $caFile = $cacheDir . DIRECTORY_SEPARATOR . 'aiven-ca.pem';
            @file_put_contents($caFile, $sslCaContent);
            if (is_file($caFile)) {
                $this->default['encrypt'] = [
                    'ssl_ca'     => $caFile,
                    'ssl_verify' => false,
                ];
            }
        }

        // Turn off DB debug in production to avoid exposing queries
        if (ENVIRONMENT === 'production') {
            $this->default['DBDebug'] = false;
        }
    }

    /**
     * Get first non-empty value from env ($_ENV, $_SERVER, getenv).
     *
     * @param list<string> $keys
     * @return string|int|float|bool|null
     */
    protected function getEnvValue(array $keys)
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
    protected function parseDatabaseUri(string $uri): ?array
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
