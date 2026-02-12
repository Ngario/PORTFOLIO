<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class ShowTables extends BaseCommand
{
    /**
     * Command grouping for `php spark` list output.
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * Full command name (run: php spark db:show-tables).
     *
     * @var string
     */
    protected $name = 'db:show-tables';

    /**
     * Short description shown in `php spark`.
     *
     * @var string
     */
    protected $description = 'List database tables and their columns.';

    /**
     * Usage shown in `php spark help`.
     *
     * @var string
     */
    protected $usage = 'db:show-tables';

    public function run(array $params)
    {
        try {
            $db = Database::connect();
        } catch (\Throwable $e) {
            CLI::error('Failed to connect to the database.');
            CLI::write($e->getMessage());
            return;
        }

        try {
            $tables = $db->listTables();
        } catch (\Throwable $e) {
            CLI::error('Could not list tables.');
            CLI::write($e->getMessage());
            return;
        }

        if (empty($tables)) {
            CLI::write('No tables found in the configured database.');
            return;
        }

        CLI::write('Tables and columns:', 'green');

        foreach ($tables as $table) {
            CLI::newLine();
            CLI::write($table, 'yellow');

            try {
                $fields = $db->getFieldNames($table);
            } catch (\Throwable $e) {
                CLI::error('  (Could not read columns: ' . $e->getMessage() . ')');
                continue;
            }

            if (empty($fields)) {
                CLI::write('  (No columns returned)');
                continue;
            }

            foreach ($fields as $field) {
                CLI::write('  - ' . $field);
            }
        }
    }
}

