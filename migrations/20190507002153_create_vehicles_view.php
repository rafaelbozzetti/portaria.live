<?php


use \Rapid\Migration\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateVehiclesView extends Migration
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
        DB::statement("
            CREATE VIEW vehicles_information 
            AS
            SELECT
                vehicles.id,
                vehicles.model,
                vehicles.color,
                vehicles.plate,
                vehicles.type,
                people.name as people_name,
                visitors.name as visitor_name
            FROM
                vehicles
                LEFT JOIN people ON vehicles.people_id = people.id 
                LEFT JOIN visitors ON vehicles.visitor_id = visitors.id
            ORDER BY people.id DESC
        ");
    }

    public function down()
    {
        DB::statement(" DROP VIEW vehicles_information");

    }
}
