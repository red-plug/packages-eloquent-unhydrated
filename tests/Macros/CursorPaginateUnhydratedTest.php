<?php

namespace RedPlug\EloquentUnhydrated\Tests\Macros;

use RedPlug\EloquentUnhydrated\Tests\Fixtures\Models\User;
use RedPlug\EloquentUnhydrated\Tests\TestCase;

class CursorPaginateUnhydratedTest extends TestCase
{
    public function test_user_model_has_cursor_paginate_unhydrated_macro(): void
    {
        $this->assertTrue(
            User::hasGlobalMacro('cursorPaginateUnhydrated')
        );
    }
}
