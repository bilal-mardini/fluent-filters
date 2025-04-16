<?php
namespace BilalMardini\FluentFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ReflectionMethod;

abstract class FilterEngine implements FilterEngineInterface
{
    /**
     * @var Request The incoming HTTP request
     */
    protected $request;

    /**
     * @var Builder The query builder instance
     */
    protected $query;

    /**
     * FilterEngine constructor.
     * 
     * @param Request $request The incoming request containing filter parameters
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the current request instance.
     * 
     * @return Request The request instance
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Hydrate the filter engine with query parameters.
     * 
     * This method creates a new instance of the filter engine using the query parameters.
     * 
     * @param array $queries The query parameters to be used for filtering
     * @return static Returns a new instance of the filter engine
     */
    public static function hydrate(array $queries)
    {
        $request = new Request($queries);

        return (new static($request));  
    }

    /**
     * Apply filters to the provided query builder instance.
     * 
     * This method will iterate over all request parameters and apply any matching filter methods
     * to the query builder.
     * 
     * @param Builder $query The query builder instance to which the filters will be applied
     * @return Builder The query builder instance with applied filters
     */
    public function applyFiltersToQuery(Builder $query)
    {
        $this->query = $query;

        foreach ($this->getRequest()->all() as $filter => $value) {
            if ($this->canApplyFilter($filter, $value)) {
                call_user_func([$this, Str::camel($filter)], $value);
            }
        }

        return $query;
    }

    /**
     * Determine if a filter can be applied based on its value.
     * 
     * This method checks whether the filter method exists, if the filter value is valid, 
     * and if the validation passes.
     * 
     * @param string $filter The filter name
     * @param mixed $value The filter value
     * @return bool True if the filter can be applied, false otherwise
     */
    protected function canApplyFilter($filter, $value)
    {
        $method = Str::camel($filter);

        if (!method_exists($this, $method)) {
            return false;
        }

        if ($value !== '' && $value !== null) {
            $data = $this->getRequest()->only($filter);

            $rules = Arr::only($this->getRules(), $filter);

            return !Validator::make($data, $rules)->fails();
        }

        return (new ReflectionMethod($this, $method))->getNumberOfParameters() === 0;
    }

    /**
     * Get the validation rules for the filters.
     * 
     * This method can be overridden by subclasses to provide specific validation rules 
     * for the filters.
     * 
     * @return array The validation rules for the filters
     */
    protected function getRules()
    {
        return [];
    }
}
