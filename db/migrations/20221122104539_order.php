<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Order extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $items = $this->table('orders');

        $items
            ->addColumn('number', 'string', ['limit' => 45, 'null' => false])
            ->addColumn('total', 'decimal')
            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['number'], ['unique' => true]);

        $items->create();

        $refTable = $this->table('order_items');
        $refTable
            ->addColumn('order_id', 'integer', ['limit' => 45, 'null' => false])
            ->addColumn('item_id', 'integer', ['limit' => 45, 'null' => false])
            ->addColumn('quantity', 'integer', ['limit' => 45, 'null' => false])

            ->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->addForeignKey('item_id', 'items', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])

            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP']);
        
        $refTable->create();
    }
}
