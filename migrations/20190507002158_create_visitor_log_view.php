<?php


use \Rapid\Migration\Migration;
use Illuminate\Database\Capsule\Manager as DB;

class CreateVisitorLogView extends Migration
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
            CREATE VIEW visitor_log_information 
            AS
            SELECT
                visitor_log.id,
                visitor_log.type,
                visitors.created_at,
                vehicles.model,
                vehicles.color,
                vehicles.plate,
                people.name as people_name,
                visitors.name as visitor_name
            FROM
                visitor_log
                LEFT JOIN people ON visitor_log.people_id = people.id 
                LEFT JOIN visitors ON visitor_log.visitor_id = visitors.id
                LEFT JOIN vehicles ON visitor_log.vehicle_id = vehicles.id
            ORDER BY visitor_log.id DESC
        ");
    }

    public function down()
    {
        DB::statement(" DROP VIEW visitor_log_information");

    }
}
