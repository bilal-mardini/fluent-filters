<?php
namespace BilalMardini\FluentFilters\Traits;
use Illuminate\Database\Eloquent\Builder;
trait HasFilters
{
     /**
     * Boot the HasFilters trait for the model.
     * 
     * This method is automatically called when the model is booted. It checks if a scope method
     * based on the model's class name exists. If it does, it registers a global scope for 
     * applying filters on the model's query builder. The filters are retrieved from the request 
     * and applied using a dynamically created filter engine class.
     *
     * The scope method is dynamically named based on the model's class name, following the format:
     * "{model_name}Scope". For example, if the model is `Product`, it will check for a method 
     * called `productScope`.
     *
     * The filter engine is resolved from the container based on the model's name (e.g., 
     * `App\Filters\ProductFilter` for the `Product` model) and is used to hydrate the filters 
     * from the request. The filters are then applied to the builder using the `applyFilters` method.
     * 
     * @return void
     */
    public static function bootHasFilters()
    {
        $modelClassName = class_basename(static::class);  
        $scopeMethodName = strtolower($modelClassName) . 'Scope';
        if (method_exists(static::class, $scopeMethodName)) {
            static::addGlobalScope('filters', function (Builder $builder) use ($scopeMethodName) {
                $filters = request()->all();  
                $filterEngine = app('App\\Filters\\' . $modelClassName . 'Filter')->hydrate($filters);  
                return $builder->applyFilters($filterEngine);
            });
        }
    }
}
