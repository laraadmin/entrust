<?php

use Illuminate\Cache\ArrayStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Laraadmin\Entrust\Traits\EntrustRoleTrait;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class EntrustRoleTraitTest extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();

        $app = m::mock('app')->shouldReceive('instance')->getMock();

        Config::setFacadeApplication($app);
        Config::swap(m::mock('config'));

        Cache::setFacadeApplication($app);
        Cache::swap(m::mock('cache'));
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testAttachAndDetachPermissionClearCachedPermissions()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $permissionObject = m::mock('Permission');
        $role = m::mock('HasPermissionRole')->makePartial()->shouldAllowMockingProtectedMethods();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $permissionObject->shouldReceive('getKey')->andReturn(1);

        $role->shouldReceive('perms')->andReturn($role);
        $role->shouldReceive('attach')->with(1)->once()->ordered();
        $role->shouldReceive('detach')->with(2)->once()->ordered();

        Cache::shouldReceive('getStore')->twice()->andReturn(new ArrayStore);
        Config::shouldReceive('get')->twice()->with('entrust.permission_role_table')->andReturn('permission_role');
        Cache::shouldReceive('tags')->twice()->with('permission_role')->andReturn($tagged = m::mock('tagged'));
        $tagged->shouldReceive('flush')->twice();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $role->attachPermission($permissionObject);
        $role->detachPermission(2);
    }
}

class HasPermissionRole
{
    use EntrustRoleTrait;

    public $primaryKey = 'id';
    public $id = 1;
}
