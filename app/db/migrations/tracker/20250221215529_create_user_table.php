<?php

declare(strict_types=1);

namespace tracker_migrations;

use Phinx\Migration\AbstractMigration;

final class CreateUserTable extends AbstractMigration
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
        $table = $this->table('user');
        $table->addColumn('system_user_id', 'integer')
                ->addColumn('code', 'string', ['limit' => 255])
                ->addColumn('name', 'string', ['limit' => 255])
                ->addColumn('signature', 'string', ['limit' => 255])
                ->addColumn('email', 'string', ['limit' => 255])
                ->addColumn('password', 'string', ['limit' => 255])
                ->addColumn('status', 'string', ['limit' => 255])
                ->addColumn('role', 'string', ['limit' => 255])
                ->addColumn('last_login', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('last_ip', 'string', ['limit' => 255])
                ->addColumn('last_agent', 'string', ['limit' => 255])
                ->addColumn('last_device', 'string', ['limit' => 255])
                ->addColumn('last_location', 'string', ['limit' => 255])
                ->addColumn('last_country', 'string', ['limit' => 255])
                ->addColumn('last_region', 'string', ['limit' => 255])
                ->addColumn('last_city', 'string', ['limit' => 255])
                ->addColumn('last_latitude', 'string', ['limit' => 255])
                ->addColumn('last_longitude', 'string', ['limit' => 255])
                ->addColumn('last_time_zone', 'string', ['limit' => 255])
                ->addColumn('last_isp', 'string', ['limit' => 255])
                ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
                ->addForeignKey('system_user_id', 'system_user', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                ->create();
    }
}
