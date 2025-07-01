<?php

namespace RedPlug\EloquentUnhydrated\Macros;

use Illuminate\Pagination\Paginator;

/**
 * Returns a collection of unhydrated models.
 *
 * @param  int|null|\Closure  $perPage
 * @param  array|string  $columns
 * @param  string  $pageName
 * @param  int|null  $page
 * @param  \Closure|int|null  $total
 *
 * @mixin  \Illuminate\Database\Eloquent\Builder
 *
 * @return \Illuminate\Pagination\LengthAwarePaginator
 */
class PaginateUnhydrated
{
    public function __invoke()
    {
        /**
         * Paginate the given query.
         *
         * @param  int|null|\Closure  $perPage
         * @param  array|string  $columns
         * @param  string  $pageName
         * @param  int|null  $page
         * @param  \Closure|int|null  $total
         * @return \Illuminate\Pagination\LengthAwarePaginator
         *
         * @throws \InvalidArgumentException
         */
        return function ($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $total = null) {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            $page = $page ?: Paginator::resolveCurrentPage($pageName);

            $total = value($total) ?? $this->toBase()->getCountForPagination();

            $perPage = value($perPage, $total) ?: $this->getModel()->getPerPage();

            $results = $total
                ? $this->forPage($page, $perPage)->getUnhydrated($columns)
                : $this->getModel()->newCollection();

            /** @phpstan-ignore-next-line */
            return $this->paginator($results, $total, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        };
    }
}
