<?php

namespace RedPlug\EloquentUnhydrated\Tests\Macros;

use RedPlug\EloquentUnhydrated\Tests\Fixtures\Models\User;
use RedPlug\EloquentUnhydrated\Tests\TestCase;

class PaginateUsingCursorUnhydratedTest extends TestCase
{
    public function test_user_model_has_paginate_using_cursor_unhydrated_method(): void
    {
        $this->assertTrue(
            User::hasGlobalMacro('paginateUsingCursorUnhydrated')
        );
    }
}
