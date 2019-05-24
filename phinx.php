<?php

$config = require __DIR__ . '/app/config.php';

return [
  'paths' => [
    'migrations' => 'migrations'
  ],
  'migration_base_class' => '\Rapid\Migration\Migration',
  'environments' => [
    'default_migration_table' => 'migrations',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => 'mysql',
      'host' => $config['settings']['database']['host'],
      'name' => $config['settings']['database']['database'],
      'user' => $config['settings']['database']['username'],
      'pass' => $config['settings']['database']['password'],
      'port' => $config['settings']['database']['port']
    ]
  ]
];
