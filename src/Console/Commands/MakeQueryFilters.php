<?php

namespace BilalMardini\FluentFilters\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Artisan command to generate query filters.
 */
class MakeQueryFilters extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new query filters class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Query filters';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/query_filters.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $path = Config::get('query_filters.path');
        $path = Str::start(ltrim($path, '/'), 'app/');
        $path = preg_replace('#^app\/#', '', $path);
        $namespace = implode('\\', array_map('ucfirst', explode('/', $path)));
        return $rootNamespace . '\\' . $namespace;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceFilters($stub);
    }

    /**
     * Replace filters for the given stub.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceFilters($stub)
    {
        parse_str($this->argument('filters'), $rawFilters);

        if (empty($rawFilters)) {
            return str_replace('DummyFilters', PHP_EOL . '    //' . PHP_EOL, $stub);
        }

        $filters = '';
        $filterStub = file_get_contents(__DIR__ . '/../../../stubs/filter.stub');

        // Loop through each filter and create filter methods
        foreach ($rawFilters as $queryParameter => $parameterName) {
            $filterName = Str::camel($queryParameter);
            $parameterVariable = $parameterName === '' ? '' : '$' . $parameterName;
            $parameterDoc = $parameterName === '' ? '' : '@param mixed $' . $parameterName . PHP_EOL . '     * ';
            $search = ['dummyQueryParameter', 'dummyParameterDoc', 'dummyFilter', 'dummyParameter'];
            $replace = [$queryParameter, $parameterDoc, $filterName, $parameterVariable];
            $filters .= str_replace($search, $replace, $filterStub);
        }

        return str_replace('DummyFilters', $filters, $stub);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the filter class (e.g., ProductFilter)'],

            ['filters', InputArgument::OPTIONAL, "A string of filters in the format 'column_name=value&another_column=type'"],
       ];
    }
}
