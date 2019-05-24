<?php

namespace Rapid\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration {

    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;

    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    public function init()  {

	     $this->capsule = new Capsule;

        $this->capsule->addConnection([
          'driver'    => getenv('DB_CONNECTION'),
          'host'      => getenv('DB_HOST'),
          'database'  => getenv('DB_DATABASE'),
          'username'  => getenv('DB_USERNAME'),
          'password'  => getenv('DB_PASSWORD'),
          'port'      => getenv('DB_PORT'),
          'charset'   => 'utf8',
          'collation' => 'utf8_unicode_ci',
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
	      $this->schema = $this->capsule->schema();

    }
}

