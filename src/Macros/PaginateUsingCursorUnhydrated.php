<?php

namespace RedPlug\EloquentUnhydrated\Macros;

use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Str;
use Illuminate\Database\Query\Expression;

/**
 * Returns a collection of unhydrated models
 * 
 * @param  int  $perPage
 * @param  array|string  $columns
 * @param  string  $cursorName
 * @param  \Illuminate\Pagination\Cursor|string|null  $cursor
 *
 * @mixin  \Illuminate\Database\Eloquent\Builder
 * @return \Illuminate\Contracts\Pagination\CursorPaginator
 */
class PaginateUsingCursorUnhydrated
{
    public function __invoke()
    {
        /**
         * Paginate the given query using a cursor paginator.
         *
         * @param  int  $perPage
         * @param  array|string  $columns
         * @param  string  $cursorName
         * @param  \Illuminate\Pagination\Cursor|string|null  $cursor
         * @return \Illuminate\Contracts\Pagination\CursorPaginator
         * 
         * @throws \Throwable
         */
        return function ($perPage, $columns = ['*'], $cursorName = 'cursor', $cursor = null) {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            if (! $cursor instanceof Cursor) {
                $cursor = is_string($cursor)
                    ? Cursor::fromEncoded($cursor)
                    : CursorPaginator::resolveCurrentCursor($cursorName, $cursor);
            }

            $orders = $this->ensureOrderForCursorPagination(! is_null($cursor) && $cursor->pointsToPreviousItems());

            if (! is_null($cursor)) {
                // Reset the union bindings so we can add the cursor where in the correct position...
                $this->setBindings([], 'union');

                $addCursorConditions = function ($builder, $previousColumn, $originalColumn, $i) use (&$addCursorConditions, $cursor, $orders) {
                    $unionBuilders = $builder->getUnionBuilders();

                    if (! is_null($previousColumn)) {
                        $originalColumn ??= $this->getOriginalColumnNameForCursorPagination($this, $previousColumn);

                        $builder->where(
                            Str::contains($originalColumn, ['(', ')']) ? new Expression($originalColumn) : $originalColumn,
                            '=',
                            $cursor->parameter($previousColumn)
                        );

                        $unionBuilders->each(function ($unionBuilder) use ($previousColumn, $cursor) {
                            $unionBuilder->where(
                                $this->getOriginalColumnNameForCursorPagination($unionBuilder, $previousColumn),
                                '=',
                                $cursor->parameter($previousColumn)
                            );

                            $this->addBinding($unionBuilder->getRawBindings()['where'], 'union');
                        });
                    }

                    $builder->where(function ($secondBuilder) use ($addCursorConditions, $cursor, $orders, $i, $unionBuilders) {
                        ['column' => $column, 'direction' => $direction] = $orders[$i];

                        $originalColumn = $this->getOriginalColumnNameForCursorPagination($this, $column);

                        $secondBuilder->where(
                            Str::contains($originalColumn, ['(', ')']) ? new Expression($originalColumn) : $originalColumn,
                            $direction === 'asc' ? '>' : '<',
                            $cursor->parameter($column)
                        );

                        if ($i < $orders->count() - 1) {
                            $secondBuilder->orWhere(function ($thirdBuilder) use ($addCursorConditions, $column, $originalColumn, $i) {
                                $addCursorConditions($thirdBuilder, $column, $originalColumn, $i + 1);
                            });
                        }

                        $unionBuilders->each(function ($unionBuilder) use ($column, $direction, $cursor, $i, $orders, $addCursorConditions) {
                            $unionWheres = $unionBuilder->getRawBindings()['where'];

                            $originalColumn = $this->getOriginalColumnNameForCursorPagination($unionBuilder, $column);
                            $unionBuilder->where(function ($unionBuilder) use ($column, $direction, $cursor, $i, $orders, $addCursorConditions, $originalColumn, $unionWheres) {
                                $unionBuilder->where(
                                    $originalColumn,
                                    $direction === 'asc' ? '>' : '<',
                                    $cursor->parameter($column)
                                );

                                if ($i < $orders->count() - 1) {
                                    $unionBuilder->orWhere(function ($fourthBuilder) use ($addCursorConditions, $column, $originalColumn, $i) {
                                        $addCursorConditions($fourthBuilder, $column, $originalColumn, $i + 1);
                                    });
                                }

                                $this->addBinding($unionWheres, 'union');
                                $this->addBinding($unionBuilder->getRawBindings()['where'], 'union');
                            });
                        });
                    });
                };

                $addCursorConditions($this, null, null, 0);
            }

            $this->limit($perPage + 1);

            return $this->cursorPaginator($this->getUnhydrated($columns), $perPage, $cursor, [
                'path' => Paginator::resolveCurrentPath(),
                'cursorName' => $cursorName,
                'parameters' => $orders->pluck('column')->toArray(),
            ]);
        };
    }
}
