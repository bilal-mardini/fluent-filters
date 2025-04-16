<?php
namespace BilalMardini\FluentFilters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterEngineInterface
{
    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @return Builder
     */
    public function applyFiltersToQuery(Builder $query): Builder;

    /**
     * Get the request instance.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest();

    /**
     * Hydrate the filter with the provided query parameters.
     *
     * @param array $queries
     * @return FilterEngineInterface
     */
    public static function hydrate(array $queries): FilterEngineInterface;

    /**
     * Get the validation rules for the filters.
     *
     * @return array
     */
    protected function getRules(): array;
}
