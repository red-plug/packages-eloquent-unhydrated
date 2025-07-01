<?php

namespace RedPlug\EloquentUnhydrated;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class EloquentUnhydratedServiceProvider extends ServiceProvider
{
    public function register()
    {
        Collection::make($this->macros())
            ->reject(fn ($class, $macro) => EloquentBuilder::hasGlobalMacro($macro))
            ->each(fn ($class, $macro) => EloquentBuilder::macro($macro, $this->app->make($class)()));
    }

    protected function macros(): array
    {
        return [
            'cursorPaginateUnhydrated' => \RedPlug\EloquentUnhydrated\Macros\CursorPaginateUnhydrated::class,
            'getUnhydrated' => \RedPlug\EloquentUnhydrated\Macros\GetUnhydrated::class,
            'paginateUnhydrated' => \RedPlug\EloquentUnhydrated\Macros\PaginateUnhydrated::class,
            'paginateUsingCursorUnhydrated' => \RedPlug\EloquentUnhydrated\Macros\PaginateUsingCursorUnhydrated::class,
            'simplePaginateUnhydrated' => \RedPlug\EloquentUnhydrated\Macros\SimplePaginateUnhydrated::class,
        ];
    }
}
