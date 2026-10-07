<?php

namespace Tests\Feature;

use App\Http\Middleware\ClinicalDepartmentMiddleware;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ClinicalMiddlewareAliasTest extends TestCase
{
    public function test_clinical_middleware_alias_is_registered(): void
    {
        $kernel = $this->app->make(Kernel::class);

        $this->assertSame(
            ClinicalDepartmentMiddleware::class,
            $kernel->getMiddlewareAliases()['clinical'] ?? null
        );
    }

    public function test_supervisor_is_allowed_to_access_clinical_rosters(): void
    {
        $department = new Department(['DepartmentName' => 'Administration']);
        $user = new Employee(['role_id' => 2]);
        $user->setRelation('department', $department);

        Auth::shouldReceive('user')->andReturn($user);

        $middleware = new ClinicalDepartmentMiddleware();
        $request = Request::create('/clinical-rosters', 'GET');

        $response = $middleware->handle($request, function ($request) {
            return response('ok');
        });

        $this->assertSame('ok', $response->getContent());
    }
}
