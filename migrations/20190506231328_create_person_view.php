<?php


use \Rapid\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

class CreatePersonView extends Migration
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
            CREATE VIEW people_information 
            AS
            SELECT
                people.id,
                people.name,
                people.email,
                people.type,
                vehicles.model,
                vehicles.color,
                vehicles.plate,
                units.name as unit_name,
                blocks.name as block_name
            FROM
                people
                LEFT JOIN vehicles ON vehicles.people_id = people.id
                LEFT JOIN units ON units.id = people.unit_id 
                LEFT JOIN blocks ON units.block_id = blocks.id 
            WHERE people.type = 1
            ORDER BY people.id
        ");
    }

    public function down()
    {
        DB::statement(" DROP VIEW people_information");

    }
}
