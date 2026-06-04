<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ADMIN</title>
</head>

<body>
    Halaman admin
    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
        @csrf
        <button type="submit"
            class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 font-medium transition-all duration-300 ease-in-out cursor-pointer"
            title="Keluar">
            logout
        </button>
    </form>
</body>

</html>
