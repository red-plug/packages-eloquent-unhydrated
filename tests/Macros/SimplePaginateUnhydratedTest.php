<?php

namespace RedPlug\EloquentUnhydrated\Tests\Macros;

use RedPlug\EloquentUnhydrated\Tests\Fixtures\Models\User;
use RedPlug\EloquentUnhydrated\Tests\TestCase;

class SimplePaginateUnhydratedTest extends TestCase
{
    public function test_user_model_has_simple_paginate_unhydrated_method(): void
    {
        $this->assertTrue(
            User::hasGlobalMacro('simplePaginateUnhydrated')
        );
    }
}
