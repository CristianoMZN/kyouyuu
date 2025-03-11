<?php

declare(strict_types=1);

namespace tracker_migrations;

use Phinx\Migration\AbstractMigration;

final class CreatePeerTable extends AbstractMigration
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
        $table = $this->table('peer');
        $table->addColumn('owner', 'integer', ['signed' => false])
                ->addColumn('hash', 'string', ['limit' => 255])
                ->addColumn('ip', 'string', ['limit' => 255])
                ->addColumn('port', 'integer', ['signed' => false])
                ->addColumn('uploaded', 'integer', ['signed' => false])
                ->addColumn('downloaded', 'integer', ['signed' => false])
                ->addColumn('left', 'integer', ['signed' => false])
                ->addColumn('status', 'string', ['limit' => 255])
                ->addColumn('last_announce', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('file', 'integer', ['signed' => false])
                ->addForeignKey('owner', 'user', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                ->addForeignKey('file', 'file', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                ->addIndex(['owner'])
                ->addIndex(['hash'], ['unique' => true])
                ->create();
    }
}
