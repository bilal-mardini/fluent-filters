<?php
// tests/Unit/ModelWithFiltersTest.php
namespace BilalMardini\FluentFilters\Tests;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class ModelWithFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_are_applied_to_query()
    {

        $product1 = Product::create(['name' => 'Laptop', 'price' => 1000]);
        $product2 = Product::create(['name' => 'Phone', 'price' => 500]);

        Request::merge(['price' => 500]);

        $products = Product::filter()->get();

        $this->assertCount(1, $products);
        $this->assertEquals('Phone', $products->first()->name);
    }
}
