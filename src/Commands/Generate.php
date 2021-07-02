<?php

namespace Basduchambre\ExternalDbConnect\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Generate extends Command
{
    protected $name = 'externaldb:generate';
    protected $description = 'Command for generating migration file and model';
    protected $files;
    protected $stub;
    protected $schema;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->migration_stub = $this->files->get(__DIR__ . '/../../stubs/migration.stub');
        $this->model_stub = $this->files->get(__DIR__ . '/../../stubs/model.stub');
    }

    public function handle()
    {
        $this->info('Starting de generation of the migration file ...');
        if (!$this->configExists('externaldb.php')) {
            $this->error('Config file missing, trying to publish it now ...');
            $this->call('externaldb:install');
        }

        $columns = config('externaldb.migration.columns');

        if (!count($columns) > 0) {
            return $this->error('No colums set in the config file, aborting ...');
        }

        $this->info('Config found, the following columns are ready to be generated:');

        foreach ($columns as $column) {
            $rows[] = [$column['name'], $column['type'], $column['default'], $column['nullable']];
        }

        $this->table(['Name', 'Type', 'default', 'nullable'], $rows);

        if ($this->confirm('Do you want to create a migration file with these columns?')) {
            $this->generateMigrationFile($columns);
        }
    }

    private function configExists($fileName): bool
    {
        return File::exists(config_path($fileName));
    }

    private function generateMigrationFile($columns)
    {
        $this->line('Starting the generation ....');

        $migrationName = $this->ask('Give the migration a name, please use snake_case');

        if ($migrationName == '') {
            $migrationName = 'external_db_connect';
        }
        $className = Str::studly($migrationName);
        $tableName = Str::snake($migrationName);
        $schema = implode("\n" . str_repeat(' ', 12), $this->parseSchema($columns));

        $this->migration_stub = str_replace('[TableName:Studly]', $className, $this->migration_stub);
        $this->migration_stub = str_replace('[TableName]', $tableName, $this->migration_stub);
        $this->migration_stub = str_replace('[Schema]', $schema, $this->migration_stub);

        $this->info($this->migration_stub);

        $this->saveMigrationFile($tableName);

        if ($this->confirm('Do you want to create a model for this migration?')) {
            $this->generateModelFile($className, $tableName);
        }
    }

    private function generateModelFile($className, $tableName)
    {
        $this->line('Creating the model ....');

        $this->model_stub = str_replace('[ModelName:Studly]', $className, $this->model_stub);
        $this->model_stub = str_replace('[TableName]', $tableName, $this->model_stub);

        $this->info($this->model_stub);

        $this->saveModelFile($className);
    }

    private function parseSchema($columns)
    {
        $this->schema = [];

        foreach ($columns as $field) {
            $syntax = sprintf("\$table->%s('%s')", $field['type'], $field['name']);

            if ($field['default'] != '') {
                $syntax .= sprintf("->default('%s')", $field['default']);
            }

            if ($field['nullable']) {
                $syntax .= '->nullable()';
            }

            $this->schema[] = $syntax .= ';';
        }

        return $this->schema;
    }

    private function saveMigrationFile($name)
    {
        $fileName = base_path() . '/database/migrations/' . date('Y_m_d_His') . '_create_' . $name . '_table.php';

        return $this->files->put($fileName, $this->migration_stub);
    }

    private function saveModelFile($name)
    {
        $fileName = base_path() . '/app/' . $name . '.php';

        if (!file_exists($fileName)) {

            return $this->files->put($fileName, $this->model_stub);
        } else {
            if ($this->shouldOverwriteModel()) {
                $this->info('Overwriting model file...');
                return $this->files->put($fileName, $this->model_stub);
            } else {
                return $this->info('Existing model was not overwritten');
            }
        }
    }

    private function shouldOverwriteModel()
    {
        return $this->confirm(
            'Model file already exists. Do you want to overwrite it?',
            false
        );
    }
}
