<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('backend.layout.style')
</head>

<body>

    @include('backend.layout.sidenav')

    <main class="content">

        @include('backend.layout.header')


        @yield('content')


        @include('backend.layout.footer')

    </main>


    @include('backend.layout.script')



</body>


</html>
