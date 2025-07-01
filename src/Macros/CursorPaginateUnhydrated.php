<?php

namespace RedPlug\EloquentUnhydrated\Macros;

/**
 * Returns a collection of unhydrated models.
 *
 * @param  int|null  $perPage
 * @param  array|string  $columns
 * @param  string  $cursorName
 * @param  \Illuminate\Pagination\Cursor|string|null  $cursor
 *
 * @mixin  \Illuminate\Database\Eloquent\Builder
 *
 * @return \Illuminate\Contracts\Pagination\CursorPaginator
 */
class CursorPaginateUnhydrated
{
    public function __invoke()
    {
        /**
         * Paginate the given query into a cursor paginator.
         *
         * @param  int|null  $perPage
         * @param  array|string  $columns
         * @param  string  $cursorName
         * @param  \Illuminate\Pagination\Cursor|string|null  $cursor
         * @return \Illuminate\Contracts\Pagination\CursorPaginator
         *
         * @throws \Throwable
         */
        return function ($perPage = null, $columns = ['*'], $cursorName = 'cursor', $cursor = null) {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            $perPage = $perPage ?: $this->getModel()->getPerPage();

            return $this->paginateUsingCursorUnhydrated($perPage, $columns, $cursorName, $cursor);
        };
    }
}
