<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUserToOrdersTable extends AbstractMigration
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
        $orders = $this->table('orders');

        $orders
            ->addColumn('placed_by', 'integer', ['limit' => 45, 'null' => true])
            ->addForeignKey('placed_by', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->save();
    }
}
