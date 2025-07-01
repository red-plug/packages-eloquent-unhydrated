<?php

namespace RedPlug\EloquentUnhydrated\Tests\Macros;

use RedPlug\EloquentUnhydrated\Tests\Fixtures\Models\User;
use RedPlug\EloquentUnhydrated\Tests\TestCase;

class GetUnhydratedTest extends TestCase
{
    public function test_user_model_has_get_unhydrated_method(): void
    {
        $this->assertTrue(
            User::hasGlobalMacro('getUnhydrated')
        );
    }
}
