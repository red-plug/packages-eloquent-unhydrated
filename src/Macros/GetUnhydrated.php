<?php

namespace RedPlug\EloquentUnhydrated\Macros;

/**
 * Returns a collection of unhydrated models
 * 
 * @param  array|string  $columns
 * 
 * @mixin  \Illuminate\Database\Eloquent\Builder
 * @return \Illuminate\Database\Eloquent\Collection<int, TModel>
 */
class GetUnhydrated
{
    public function __invoke()
    {
        /**
         * Execute the query as a "select" statement.
         *
         * @param  array|string  $columns
         * @return \Illuminate\Database\Eloquent\Collection<int, TModel>
         * 
         * @throws \Throwable
         */
        return function($columns = ['*']) {
            /** @var \Illuminate\Database\Eloquent\Builder  $this */
            return $this->toBase()->get($columns);
        };
    }
}
