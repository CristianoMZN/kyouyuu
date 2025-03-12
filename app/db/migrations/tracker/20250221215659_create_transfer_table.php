<?php

declare(strict_types=1);

namespace tracker_migrations;

use Phinx\Migration\AbstractMigration;

final class CreateTransferTable extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('transfer');
        $table->addColumn('owner', 'integer', ['signed' => false])
                ->addColumn('file', 'integer')
                ->addColumn('peer', 'integer')
                ->addColumn('status', 'string', ['limit' => 255])
                ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
                ->addIndex(['owner'])
                ->addIndex(['file'])
                ->addIndex(['peer'])
                ->create();
    }
}
