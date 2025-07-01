<?php

namespace RedPlug\EloquentUnhydrated\Macros;

use Illuminate\Pagination\Paginator;

/**
 * Returns a collection of unhydrated models
 * 
 * @param  int|null  $perPage
 * @param  array|string  $columns
 * @param  string  $pageName
 * @param  int|null  $page
 * @mixin  \Illuminate\Database\Eloquent\Builder
 * @return \Illuminate\Contracts\Pagination\Paginator
 */
class SimplePaginateUnhydrated
{
    public function __invoke()
    {
        /**
         * Paginate the given query into a simple paginator.
         *
         * @param  int|null  $perPage
         * @param  array|string  $columns
         * @param  string  $pageName
         * @param  int|null  $page
         * @return \Illuminate\Contracts\Pagination\Paginator
         * 
         * @throws \Throwable
         */
        return function($perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            $page = $page ?: Paginator::resolveCurrentPage($pageName);

            $perPage = $perPage ?: $this->model->getPerPage();

            // Next we will set the limit and offset for this query so that when we get the
            // results we get the proper section of results. Then, we'll create the full
            // paginator instances for these results with the given page and per page.
            $this->skip(($page - 1) * $perPage)->take($perPage + 1);

            return $this->simplePaginator($this->getUnhydrated($columns), $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        };
    }
}
