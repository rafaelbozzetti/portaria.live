<?php

defined('DS') ?: define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') ?: define('ROOT', dirname(__DIR__) . DS);
$reference = require __DIR__ . '/reference.php';

if (file_exists(ROOT . '.env')) {
	$dotenv = Dotenv\Dotenv::create(ROOT);
	$dotenv->load();
}

return [
    'settings' => [
        'displayErrorDetails'    => getenv('APP_DEBUG') === 'true' ? true : false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'app'                    => [
            'name' => getenv('APP_NAME'),
            'url'  => getenv('APP_URL'),
            'env'  => getenv('APP_ENV'),
        ],
        'renderer'               => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        'logger'                 => [
            'name'  => getenv('APP_NAME'),
            'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'database'               => [
            'driver'    => getenv('DB_CONNECTION'),
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_DATABASE'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
            'port'      => getenv('DB_PORT'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        'cors' => null !== getenv('CORS_ALLOWED_ORIGINS') ? getenv('CORS_ALLOWED_ORIGINS') : '*',
        'jwt'  => [
            'secret' => getenv('JWT_SECRET'),
            'secure' => false,
            "header" => "Authorization",
            "regexp" => "/Token\s+(.*)$/i",
            'passthrough' => ['OPTIONS']
        ],
    ],
];
