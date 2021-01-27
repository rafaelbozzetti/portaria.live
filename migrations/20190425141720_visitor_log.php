<?php


use \Rapid\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class VisitorLog extends Migration
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
        $this->schema->create('visitor_log', function (Blueprint $table) {
	        $table->increments('id');
	        $table->integer('type');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->unsignedInteger('people_id');
            $table->unsignedInteger('visitor_id');
            $table->unsignedInteger('vehicle_id')->nullable();
        });
    }

    public function down()
    {
        
        $this->schema->dropIfExists('visitor_log');
    }
}
