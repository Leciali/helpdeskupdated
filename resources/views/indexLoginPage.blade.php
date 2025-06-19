<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[url('/asset/Background_Login.jpeg')] bg-cover bg-center flex items-center justify-center min-h-screen">
    <div class="rounded-lg flex max-w-3xl w-full shadow-lg overflow-hidden">
        <div class="w-1/2 p-8 bg-[#f3f4f6] rounded-l-lg flex items-center justify-center">
            <img class="w-full h-auto" height="400" src="asset/pgn_logo.png" width="400">
        </div>

        <div class="w-1/2 p-8 bg-white bg-opacity-50 backdrop-blur-sm rounded-r-lg">
            <h1 class="text-2l font-bold text-gray-800">
                Welcome to Pertagas HelpDesk!
                <br>
                <span class="font-medium">Silahkan login</span>
            </h1>
            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            html: "Email/Password yang anda masukkan masih salah, coba ulang kembali.",
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            @endif
            <form class="mt-8" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-[#213555] text-sm font-bold mb-2" for="email">
                        Login your account
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" placeholder="email" type="email" autofocus required>
                </div>
                <div class="mb-5">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-black mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" placeholder="password" type="password" required>
                </div>
                <div class="mb-4">
                    <button class="bg-[#003092] hover:bg-[#80C4E9] text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline w-full rounded" type="submit">
                        Login
                    </button>
                </div>
                <div class="text-center">
                    <a class="inline-block align-baseline font-bold text-sm text-[#213555] hover:text-[#80C4E9]" href="#">
                        Create Account
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>