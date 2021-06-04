<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Devtool\Generator\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @Command
 */
class CreateServiceCommand extends GeneratorCommand
{
    protected $name = 'gen:service';

    public function __construct()
    {
        parent::__construct($this->name);
        $this->setDescription('Create a new bxy service class');
    }

    /**
     * Execute the console command.
     *
     * @return null|bool
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $inputs = $this->getNameInput();
        $name = $this->qualifyClass($inputs['name']);

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if (($input->getOption('force') === false) && $this->alreadyExists($inputs['name'])) {
            $output->writeln(sprintf('<fg=red>%s</>', $name . ' already exists!'));
            return 0;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);
        file_put_contents($path, $this->buildModelClass($name));

        $output->writeln(sprintf('<info>%s</info>', $name . ' created successfully.'));
        return  1;
    }

    /**
     * @param $name
     * @return string|string[]
     */
    protected function buildModelClass($name)
    {
        $stub = file_get_contents($this->getStub());
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * @param $stub
     * @param $name
     * @return string|string[]
     */
    protected function replaceModel($stub, $name)
    {
        return str_replace('%MODEL%', $name, $stub);
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/service.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Service';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return [
            'name' => trim($this->input->getArgument('name')),
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service class'],
        ];
    }
}
