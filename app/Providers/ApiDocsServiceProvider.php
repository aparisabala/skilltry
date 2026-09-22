<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Tag;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/** HMS documentation pattern, scoped to this application's admin API. */
class ApiDocsServiceProvider extends ServiceProvider
{
    private const GROUPS = [
        'Authentication' => 'Authentication',
        'Dashboard' => 'Dashboard',
        'Profile setup' => 'Dashboard / Account / Profile Setup',
        'Account reset' => 'Dashboard / Account / Password Reset',
        'HR roles' => 'Human Resource / User Role',
        'HR users' => 'Human Resource / User',
        'HR permissions' => 'Human Resource / User Policy',
    ];

    public function boot(): void
    {
        Scramble::configure()
            ->routes(fn (Route $route) => str_starts_with($route->uri(), 'api/v1/'))
            ->withOperationTransformers(function (Operation $operation, RouteInfo $routeInfo) {
                $operation->setTags(array_map(fn ($tag) => self::GROUPS[$tag] ?? $tag, $operation->tags));
                $action = class_basename($routeInfo->className() ?? '').'@'.$routeInfo->methodName();
                $permission = config('hrm.ability_overrides', [])[$action] ?? null;
                if (is_string($permission)) {
                    $operation->description = trim(($operation->description ?? '')."\n\nUser policy permission: `{$permission}`.");
                }
            })
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $used = [];
                foreach ($openApi->paths as $path) {
                    foreach ($path->operations as $operation) {
                        $used = array_merge($used, $operation->tags);
                    }
                }
                $used = array_values(array_unique($used));
                $order = array_flip(array_values(self::GROUPS));
                usort($used, fn ($a, $b) => ($order[$a] ?? 999) <=> ($order[$b] ?? 999));
                $openApi->tags = array_map(fn ($name) => new Tag($name), $used);
            });

        Gate::define('viewApiDocs', fn ($user = null) => match (config('hrm.api_docs', 'local')) {
            'public' => true,
            'off' => false,
            default => app()->environment('local'),
        });
    }
}
