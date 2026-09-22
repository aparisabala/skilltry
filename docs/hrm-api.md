# Admin API documentation

## Scramble

- Interactive documentation: `/docs/api`
- Generated OpenAPI JSON: `/docs/api.json`
- Exported specification: [openapi.json](openapi.json)
- Regenerate the export: `php artisan scramble:export`
- Clear a previously generated Scramble cache: `php artisan scramble:clear`

These paths are relative to the application URL. Set `APP_URL` to the correct application base URL when exporting from the command line. Scramble is configured in `config/scramble.php`; the HMS-style `ApiDocsServiceProvider` groups operations in sidebar order and adds each HR permission. Set `HRM_API_DOCS=local` (default), `public`, or `off` to control both documentation URLs through the `viewApiDocs` gate. `off` also disables local access. See [Scramble installation and access configuration](https://scramble.dedoc.co/installation).

The interactive reference follows the HMS Stoplight Elements layout, with sidebar groups `Human Resource / User Role`, `Human Resource / User`, and `Human Resource / User Policy`, plus authentication and dashboard endpoints. Account profile setup and password reset are grouped under `Dashboard / Account`. Its server base is `/api/v1`; displayed operation paths begin with `/admin`. It includes request schemas, file uploads and response envelopes. Scramble displays PUT for routes accepting both PUT and PATCH; either method works at runtime.

## Controller and repository structure

Every admin API route uses a dedicated controller under `app/Http/Controllers/Api/V1/Admin`. API controllers do not extend or invoke web controllers. They inject repository interfaces through their constructors, bound in `RepositoryServiceProvider`.

- HR API controllers inject the existing `App\Repositories\Admin\Hrm\...\I...Repository` interfaces, sharing the same business logic as the web module.
- Authentication, dashboard, profile setup, profile updates and reset use interfaces and implementations under `App\Repositories\Api\V1\Admin`.
- `ApiController` applies HR permission checks and removes passwords, remember tokens and reset secrets from JSON responses.
- API routes mirror `routes/admin/**` in `routes/api-admin/**`, with the `/api/v1` URL prefix and `api.v1.` route-name prefix.
- Web controllers continue rendering Blade. API controllers return JSON directly; there is no custom view-converting controller dispatcher.

The removed user-modification section (OPD slots, fees and designations) remains absent.

## Mobile API permission

Enable `mobile_api_access` under **Human Resource > User Policy > Mobile API Policies** for roles that need API access. As in HMS, Super Admin retains access; other users are denied when this permission is missing. Login and every authenticated request check it, so removing access also blocks existing tokens. HR action permissions still apply separately.

## Authenticate

Only initial token issuance is public:

```http
POST /api/v1/admin/auth/login
Accept: application/json
Content-Type: application/json

{"email":"admin@example.com","password":"your-password","device_name":"client"}
```

The email field accepts an administrator email or mobile number. A successful response contains `data.token`, `token_type`, `expires_at`, `setup_required` and `user`. Tokens last 30 days.

For all protected requests use:

```http
Authorization: Bearer <data.token>
Accept: application/json
```

The `admin_api` guard uses Sanctum and the `AdminUser` provider. Session cookies alone do not authenticate API requests. The middleware supplies the token owner to repositories; do not send `auth` or `auth_uuid`.

- `GET /api/v1/admin/auth/me` returns user information, setup status and permissions.
- `POST /api/v1/admin/auth/logout` revokes the current token.
- `GET /api/user` is a current-user alias.
- Incomplete profiles may use auth/me, auth/logout and setup/profile. Other endpoints return `setup_required`.
- Mirrored login and reset routes are protected. Use auth/login to get the first token. Reset operates only on the authenticated account.

## Payloads

### HR users

Use `admin_user_role_id` in the user-list path, the index/create query, and the list request body.

Create with multipart/form-data: `admin_type=system_user`, `name`, `email`, `mobile_number`, `admin_user_role_id`, and `image` (JPG/JPEG/PNG/WEBP, maximum 2024 KB). The existing HMS repository assigns the initial password and profile-setup state; this behavior is unchanged.

Update with `name`, `email`, `mobile_number`, `status` (Active/Disabled), and optional `image`. For an image update on PHP installations that do not parse multipart PUT/PATCH, POST multipart data with `_method=PATCH` to the same resource URL.

### Roles

Create/update payload:

```json
{"name":"Teacher","code":"TC"}
```

Name is 2?253 characters; code is 2?3 characters. Create requires unique name/code; updates retain the repository validation rules.

### Lists and bulk actions

List endpoints accept DataTables fields: `draw`, `start`, `length`, `search[value]`, `columns` and `order`. User lists also require `admin_user_role_id`.

Delete-list and update-list payload:

```json
{"ids":[2,3]}
```

The inherited user/role bulk-update repositories currently make no field changes and return their existing no-change response.

### Permissions

Read user-policy or the permission-editor display endpoint to obtain permission row IDs. Update with:

```json
{"slug":[12,13],"user_access":{"12":["TC"],"13":["TC","ST"]}}
```

Despite its name, `slug` is an array of numeric permission IDs. Omitted access selections for a submitted permission ID clear its role access. `policy_name` is optional on the display endpoint.

### Profile and password

- Initial setup: `name`, `email`, `mobile_number`, `new_password`, `confim_password`. The legacy spelling `confim_password` is intentional.
- Profile update: `name`, `email`, `mobile_number`; optional image. Image is required when `img_uploaded=no`.
- Password update: `old_password`, `password`, `confirm_password`; at least 8 characters and matching confirmation.
- Reset send-code: optional own `email`; returns `data.next_step=verify-code`.
- Reset verify-code: numeric `code`; returns `data.next_step=change-pass`.
- Reset change-pass: `password`, `confirm_password`; at least 8 characters. Optional `user_uuid` must match the token owner.

Reset responses contain JSON step information instead of web form HTML. Setup/password responses retain legacy `extraData.redirect` values as informational fields; API clients should drive their own navigation.

## Responses and permissions

Successful reads use `{"success":true,"data":...}`. Mutation envelopes are unchanged. Validation or no-change results from legacy repositories may use HTTP 200 with `success=false`, `errors`, or `noUpdate`; check the body as well as the HTTP status. Login validation uses 422. Lists return DataTables `draw`, `recordsTotal`, `recordsFiltered` and `data`.

| Status | Meaning |
| --- | --- |
| 401 | Missing, invalid or expired token; incorrect credentials |
| 403 | Disabled account, incomplete setup, denied HR ability or another account targeted by reset |
| 409 | Missing/invalid role on the user page or setup already completed |
| 422 | FormRequest validation (login); legacy HR errors may instead use 200 |
| 429 | Rate limit exceeded |

HR permissions use the existing role-access Gates. `HRM_PERMISSIONS=api` enforces them on API routes by default; `all`, `log` and `off` retain the existing modes.

## Endpoint inventory

All URLs below are relative to the application base. Everything except `POST api/v1/admin/auth/login` requires a bearer token.

| Method | Path |
| --- | --- |
| GET / HEAD | `api/user` |
| GET / HEAD | `api/v1` |
| POST | `api/v1/admin/auth/login` |
| POST | `api/v1/admin/auth/logout` |
| GET / HEAD | `api/v1/admin/auth/me` |
| GET / HEAD | `api/v1/admin/dashboard` |
| GET / HEAD | `api/v1/admin/hrm/user` |
| POST | `api/v1/admin/hrm/user` |
| GET / HEAD | `api/v1/admin/hrm/user/create` |
| POST | `api/v1/admin/hrm/user/delete-list` |
| POST | `api/v1/admin/hrm/user/list` |
| POST | `api/v1/admin/hrm/user/policy/update-policy-item/display` |
| POST | `api/v1/admin/hrm/user/update-list` |
| GET / HEAD | `api/v1/admin/hrm/user/user-list/{admin_user_role_id}` |
| GET / HEAD | `api/v1/admin/hrm/user/user-policy` |
| POST | `api/v1/admin/hrm/user/user-policy` |
| GET / HEAD | `api/v1/admin/hrm/user/user-role` |
| POST | `api/v1/admin/hrm/user/user-role` |
| GET / HEAD | `api/v1/admin/hrm/user/user-role/create` |
| POST | `api/v1/admin/hrm/user/user-role/delete-list` |
| POST | `api/v1/admin/hrm/user/user-role/list` |
| POST | `api/v1/admin/hrm/user/user-role/update-list` |
| PUT / PATCH | `api/v1/admin/hrm/user/user-role/{user_role}` |
| GET / HEAD | `api/v1/admin/hrm/user/user-role/{user_role}/edit` |
| PUT / PATCH | `api/v1/admin/hrm/user/{user}` |
| GET / HEAD | `api/v1/admin/hrm/user/{user}/edit` |
| GET / HEAD | `api/v1/admin/login` |
| POST | `api/v1/admin/login` |
| GET / HEAD | `api/v1/admin/logout` |
| GET / HEAD | `api/v1/admin/reset` |
| POST | `api/v1/admin/reset/change-pass` |
| POST | `api/v1/admin/reset/send-code` |
| POST | `api/v1/admin/reset/verify-code` |
| GET / HEAD | `api/v1/admin/setup/password-update` |
| POST | `api/v1/admin/setup/password-update` |
| GET / HEAD | `api/v1/admin/setup/profile` |
| POST | `api/v1/admin/setup/profile` |
| GET / HEAD | `api/v1/admin/setup/profile-update` |
| POST | `api/v1/admin/setup/profile-update` |

## Verification

`php artisan test`: 22 tests, 388 assertions. Tests cover API/web controller separation, route parity, token lifecycle and provider isolation, role permissions, HR mutations and image upload, setup/reset repositories, and Scramble UI/specification access and payload schemas.
