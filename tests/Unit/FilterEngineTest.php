<?php

namespace BilalMardini\FluentFilters\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use FluentFilters\Filters\FilterEngine;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class FilterEngineTest extends TestCase
{
    public function test_apply_filters_to_query()
    {
        $query = \Mockery::mock(Builder::class);
        $query->shouldReceive('where')->once()->with('name', 'John')->andReturnSelf();
        $query->shouldReceive('where')->once()->with('age', 30)->andReturnSelf();

        $request = new Request([
            'name' => 'John',
            'age' => 30,
        ]);

        $filterEngine = new class($request) extends FilterEngine {
            public function getRules() {
                return [
                    'name' => 'required|string',
                    'age' => 'required|integer',
                ];
            }
        };

        $filterEngine->applyFiltersToQuery($query);

        $query->shouldHaveReceived('where')->with('name', 'John');
        $query->shouldHaveReceived('where')->with('age', 30);
    }
}
