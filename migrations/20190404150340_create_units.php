<?php


use \Rapid\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUnits extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function up()
    {

        $this->schema->create('units', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('number');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->unsignedInteger('block_id');

        });

        $this->schema->table('units', function($table) {
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->index('block_id');
            $table->index('id');
        });

    }

    public function down()
    {

        $this->schema->dropIfExists('units');

    }

}
