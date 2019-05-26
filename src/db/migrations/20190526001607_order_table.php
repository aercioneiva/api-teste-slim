<?php

use Phinx\Migration\AbstractMigration;

class OrderTable extends AbstractMigration
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
    public function change()
    {
        $table = $this->table('orders');
        $table->addColumn('status', 'string',['limit' => 100])
              ->addColumn('total', 'decimal',['precision' => 10, 'scale' => 2])
              ->addColumn('created_at', 'datetime',['null' => true])
              ->addColumn('updated_at', 'datetime',['null' => true])
              ->addColumn('cancelDate', 'datetime',['null' => true])
              ->addColumn('customer_id', 'integer', ['signed' => true])
              ->addForeignKey('customer_id',
                              'customers',
                              ['id'],
                              ['constraint' => 'customer_id'])
              ->create();
    }
}
