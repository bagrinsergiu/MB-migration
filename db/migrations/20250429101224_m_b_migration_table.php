<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MBMigrationTable extends AbstractMigration
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
        // Проверяем, существует ли таблица
        if ($this->hasTable('migrations_mapping')) {
            // Таблица уже существует, пропускаем миграцию
            return;
        }
        
        $this->table('migrations_mapping')
            ->addColumn('brz_project_id', 'integer', ['null' => true])
            ->addColumn('mb_project_uuid', 'string', ['null' => true])
            ->addColumn('changes_json', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
