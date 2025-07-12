<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\FakeService;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Exception;

/**
 * Command to generate and/or manage fake data for models.
 *
 * This command provides utilities for generating, inserting, or managing fake data in the application's database tables.
 * It is typically used for development, testing, or demo purposes to quickly populate the database with sample data.
 *
 * Usage examples:
 *   bin/cake fake [options]
 *
 * Options and arguments should be documented in the handle() method or via CakePHP's command help system.
 *
 * @package App\Command
 */
class FakeCommand extends Command
{
    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'fake';

    /**
     * Get the default command name.
     *
     * @return string The default command name for this command ("fake").
     */
    public static function defaultName(): string
    {
        return 'fake';
    }

    /**
     * Get the command description.
     *
     * @return string The description of this command for help output.
     */
    public static function getDescription(): string
    {
        return 'Generate fake data for models. Usage: bin/cake fake <model> <count>';
    }

    /**
     * Define this command's option parser, arguments, and options.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined.
     * @return \Cake\Console\ConsoleOptionParser The built parser with arguments and options.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser)
            ->setDescription(static::getDescription())
            ->addOption('list-models', [
                'short' => 'l',
                'help' => 'List available models for fake data generation',
                'boolean' => true,
            ])
            ->addOption('dry-run', [
                'short' => 'd',
                'help' => 'Show what would be generated without actually saving',
                'boolean' => true,
            ])
            ->addOption('special-fields', [
                'short' => 's',
                'help' => 'Comma-separated list of custom special fields (e.g., "avatar,logo,thumbnail")',
                'default' => '',
            ])
            ->addArgument('model', [
                'help' => 'The model name to generate fake data for',
                'required' => false,
            ])
            ->addArgument('count', [
                'help' => 'Number of fake records to generate',
                'required' => false,
            ]);

        return $parser;
    }

    /**
     * Execute the command logic for generating or managing fake data.
     *
     * @param \Cake\Console\Arguments $args The command arguments (options and positional arguments).
     * @param \Cake\Console\ConsoleIo $io The console input/output handler.
     * @return int|null|void The exit code (0 for success, 1 for error), or null for success.
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $fakeService = new FakeService();

        // Handle list models option
        if ($args->getOption('list-models')) {
            $this->listAvailableModels($io, $fakeService);

            return self::CODE_SUCCESS;
        }

        $modelName = $args->getArgument('model');
        $count = $args->getArgument('count');
        $dryRun = $args->getOption('dry-run');
        $specialFieldsOption = $args->getOption('special-fields');

        // Parse special fields
        $specialFields = [];
        if (!empty($specialFieldsOption)) {
            $specialFields = array_map('trim', explode(',', $specialFieldsOption));
        }

        // Validate required arguments
        if (empty($modelName)) {
            $io->error('Model argument is required.');
            $io->out('Available models:');
            $this->listAvailableModels($io, $fakeService);

            return self::CODE_ERROR;
        }

        if (empty($count)) {
            $io->error('Count argument is required.');
            $io->out('Usage: bin/cake fake <model> <count>');
            $io->out('Example: bin/cake fake users 10');

            return self::CODE_ERROR;
        }

        $count = (int)$count;

        // Validate model
        if (!$fakeService->isModelAvailable($modelName)) {
            $io->error("Model '{$modelName}' is not available for fake data generation.");
            $io->out('Available models:');
            $this->listAvailableModels($io, $fakeService);

            return self::CODE_ERROR;
        }

        // Validate count
        if ($count <= 0 || $count > 1000) {
            $io->error('Count must be between 1 and 1000.');

            return self::CODE_ERROR;
        }

        try {
            $io->out("Generating {$count} fake records for '{$modelName}' model...");

            if ($dryRun) {
                $io->out('DRY RUN MODE - No data will be saved');
            }

            if (!empty($specialFields)) {
                $io->out('Using custom special fields: ' . implode(', ', $specialFields));
            }

            // Generate fake data
            $entities = $fakeService->generateFakeData($modelName, $count, $specialFields);

            if ($dryRun) {
                $this->displayDryRunResults($io, $entities, $modelName);

                return self::CODE_SUCCESS;
            }

            // Save entities
            $table = $fakeService->getTable($modelName);
            $savedCount = 0;
            $errors = [];

            foreach ($entities as $entity) {
                if ($table->save($entity)) {
                    $savedCount++;
                } else {
                    $errors[] = $entity->getErrors();
                }
            }

            // Display results
            $io->success("Successfully generated {$savedCount} fake records for '{$modelName}' model.");

            if (!empty($errors)) {
                $io->warning('Failed to save ' . count($errors) . ' records due to validation errors.');
                if ($io->level() >= ConsoleIo::VERBOSE) {
                    foreach ($errors as $index => $error) {
                        $io->out('Record ' . ($index + 1) . ' errors: ' . json_encode($error));
                    }
                }
            }

            return self::CODE_SUCCESS;
        } catch (Exception $e) {
            $io->error('Error generating fake data: ' . $e->getMessage());

            return self::CODE_ERROR;
        }
    }

    /**
     * Output a list of available models for fake data generation.
     *
     * @param \Cake\Console\ConsoleIo $io The console input/output handler.
     * @param \App\Service\FakeService $fakeService The fake data service instance.
     * @return void
     */
    private function listAvailableModels(ConsoleIo $io, FakeService $fakeService): void
    {
        $models = $fakeService->getAvailableModels();

        $io->out('Available models for fake data generation:');
        $io->out('');

        foreach ($models as $key => $model) {
            $io->out("  - {$key} ({$model})");
        }

        $io->out('');
        $io->out('Usage: bin/cake fake <model> <count>');
        $io->out('Example: bin/cake fake users 10');
    }

    /**
     * Display a summary of what would be generated in dry-run mode.
     *
     * @param \Cake\Console\ConsoleIo $io The console input/output handler.
     * @param array<int, \Cake\Datasource\EntityInterface> $entities The fake entities that would be generated.
     * @param string $modelName The model name for which fake data is generated.
     * @return void
     */
    private function displayDryRunResults(ConsoleIo $io, array $entities, string $modelName): void
    {
        $io->out('');
        $io->out("DRY RUN RESULTS for '{$modelName}' model:");
        $io->out('Would generate ' . count($entities) . ' records:');
        $io->out('');

        // Show first 3 entities as examples
        $examples = array_slice($entities, 0, 3);
        foreach ($examples as $index => $entity) {
            $io->out('Record ' . ($index + 1) . ':');
            $data = $entity->toArray();

            // Hide sensitive fields
            $sensitiveFields = ['password', 'verification_token', 'two_factor_secret', 'data'];
            foreach ($sensitiveFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = '[HIDDEN]';
                }
            }

            foreach ($data as $field => $value) {
                if (is_object($value)) {
                    $value = $value->format('Y-m-d H:i:s');
                }
                $io->out("  {$field}: {$value}");
            }
            $io->out('');
        }

        if (count($entities) > 3) {
            $io->out('... and ' . (count($entities) - 3) . ' more records');
        }
    }
}
