@php
$r = \Route::getFacadeRoot()->current()->uri();
$c = "nav-md";
@endphp
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr">
<head>
    @include('admin.includes.header-resource',['tabTitle' => $tabTitle ?? "Site Title"])
</head>
<body class="authentication-bg">
    @yield('page')
    @include('admin.includes.footer-resource',["react"=>$react ?? []])
</body>
</html>