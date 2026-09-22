<?php

use App\Http\Middleware\Api\AuthenticateAdminApi;
use Dedoc\Scramble\SecurityDocumentation\MiddlewareAuthSecurityStrategy;

return [
    'api_path' => 'api/v1',
    'export_path' => 'docs/openapi.json',
    'info' => [
        'version' => '1.0.0',
        'description' => <<<'MD'
Biddaloy Human Resource and admin account API.

## Getting started
1. `POST /admin/auth/login` with `email`, `password` and optional `device_name`. Read the bearer token from `data.token`.
2. Send `Authorization: Bearer <token>` and `Accept: application/json` on protected requests. The API uses the `admin_api` guard.
3. Use JSON for writes, or `multipart/form-data` for image uploads. `POST /admin/auth/logout` revokes the current token.

All paths below are relative to `/api/v1`.

## Where to find an endpoint
Endpoints follow the admin sidebar: **Human Resource / User Role**, **Human Resource / User**, and **Human Resource / User Policy**. Account setup and password reset are grouped under **Dashboard / Account**. Authentication has its own group. Each HR operation lists its User policy permission.

| Screen | Base path |
|---|---|
| User Role | `/admin/hrm/user/user-role` |
| User | `/admin/hrm/user` |
| User Policy | `/admin/hrm/user/user-policy` |

Use the documented `POST .../list` endpoints for DataTables lists (`draw`, `start`, `length`, `search[value]`). Bulk actions use `ids`. User lists also take `admin_user_role_id`. Read each endpoint's request schema before sending a write.

## Responses
Mutations preserve the existing repository envelopes, including `success`, `errors` and `data` where present. Lists return the DataTables shape (`draw`, `recordsTotal`, `recordsFiltered`, `data`). Some legacy validation failures return HTTP 200 with `success: false`; always inspect the response body.

| HTTP | Meaning |
|---|---|
| 200 | Request handled; check `success` for legacy failures |
| 401 | Missing or invalid bearer token |
| 403 | Disabled account or missing HR permission |
| 409 | Setup required or invalid user-role selection |
| 422 | Request validation failure |
| 429 | Too many requests |

## Permissions and account setup
The account must have `mobile_api_access` (Human Resource > User Policy > Mobile API Policies). Super Admin retains access. This is checked at login and on every authenticated request, including existing tokens. HR permissions use the same role policy as the web module. Accounts needing setup must complete `/admin/setup/profile` first. The reset endpoints are authenticated and operate only on the token owner. Passwords and reset secrets are excluded from responses.

## Scope
This reference covers the existing HR and supporting admin account routes. Doctor modification screens, OPD slots, fees and designations are not included.
MD,
    ],
    'ui' => ['title' => 'Biddaloy Admin API'],
    'renderer' => 'elements',
    'renderers' => [
        'elements' => [
            'view' => 'scramble::docs',
            'theme' => 'light',
            'hideTryIt' => false,
            'hideSchemas' => false,
            'tryItCredentialsPolicy' => 'include',
            'layout' => 'responsive',
            'router' => 'hash',
        ],
    ],
    'middleware' => ['web', 'can:viewApiDocs'],
    'security_strategy' => [
        MiddlewareAuthSecurityStrategy::class,
        ['middleware' => [AuthenticateAdminApi::class, AuthenticateAdminApi::class.':*']],
    ],
];
