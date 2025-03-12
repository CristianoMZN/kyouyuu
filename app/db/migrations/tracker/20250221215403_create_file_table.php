<?php

declare(strict_types=1);

namespace tracker_migrations;

use Phinx\Migration\AbstractMigration;

final class CreateFileTable extends AbstractMigration
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
        // Creates the 'file' table
        $table = $this->table('file');
        $table->addColumn('owner', 'integer', ['signed' => false])
                ->addColumn('name', 'string', ['limit' => 255])
                ->addColumn('hash', 'string', ['limit' => 255])
                ->addColumn('size', 'integer', ['signed' => false])
                ->addColumn('type', 'string', ['limit' => 255])
                ->addColumn('cover', 'string', ['limit' => 255])
                ->addColumn('description', 'text', ['null' => true])
                ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
                ->addIndex(['owner'])
                ->addIndex(['hash'], ['unique' => true])
                ->create();
    }
}
