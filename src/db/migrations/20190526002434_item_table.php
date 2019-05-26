<?php

use Phinx\Migration\AbstractMigration;

class ItemTable extends AbstractMigration
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
        $table = $this->table('items');
        $table->addColumn('amount', 'integer')
              ->addColumn('total', 'decimal',['precision' => 10, 'scale' => 2])
              ->addColumn('price_unit', 'decimal',['precision' => 10, 'scale' => 2])
              ->addColumn('created_at', 'datetime',['null' => true])
              ->addColumn('updated_at', 'datetime',['null' => true])
              ->addColumn('cancelDate', 'datetime',['null' => true])
              ->addColumn('order_id', 'integer', ['signed' => true])
              ->addColumn('product_id', 'integer', ['signed' => true])
              ->addForeignKey('order_id',
                              'orders',
                              ['id'],
                              ['constraint' => 'order_id'])
              ->addForeignKey('product_id',
                              'products',
                              ['id'],
                              ['constraint' => 'product_id'])
              ->create();
    }
}
