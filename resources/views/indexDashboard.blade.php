<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Tickets Dashboard
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
 </head>
 <body class="bg-gray-100">
  <div class="flex h-screen">
 <!-- Sidebar -->
<div class="bg-gray-900 text-gray-300 w-40 flex flex-col h-screen">
    <div class="flex justify-center items-center py-4 border-b border-gray-700">
        <img src="asset/LogoPertamina.png" class="h-8">
    </div>

    <!-- LIST MENU (Gunakan flex-grow agar profil terdorong ke bawah) -->
    <ul class="mt-4 space-y-1 flex-grow">
        <li class="flex items-center px-3 py-1.5 hover:bg-gray-800 rounded cursor-pointer">
            <i class="fas fa-users text-xs text-gray-400"></i>
            <a href="{{ route('user.dashboard') }}" class="ml-2 text-sm font-light">All Tickets</a>
        </li>
        <li class="flex items-center px-3 py-1.5 hover:bg-blue-900 rounded cursor-pointer">
            <i class="fas fa-folder-open text-xs text-gray-400"></i>
            <a href="{{ route('user.open-ticket') }}" class="ml-2 text-sm font-light">Open</a>
        </li>
        <li class="flex items-center px-3 py-1.5 hover:bg-yellow-900 rounded cursor-pointer">
            <i class="fas fa-clock text-xs text-gray-400"></i>
            <a href="{{ route('user.pending-ticket') }}" class="ml-2 text-sm font-light">Pending</a>
        </li>
        <li class="flex items-center px-3 py-1.5 hover:bg-green-900 rounded cursor-pointer">
            <i class="fas fa-check text-xs"></i>
            <a href="{{ route('user.solved-ticket') }}" class="ml-2 text-sm font-light">Solved</a>
        </li>
        <li class="flex items-center px-3 py-1.5 hover:bg-red-900 rounded cursor-pointer">
            <i class="fas fa-times text-xs text-gray-400"></i>
            <a href="{{ route('user.report') }}" class="ml-2 text-sm font-light">Report</a>
        </li>
    </ul>

  <!-- PROFIL (Tetap di bawah) -->
    <div class="flex items-center px-3 py-3 border-t border-gray-700">
        <img class="rounded-full h-8 w-8" src="asset/user.png"/>
        <div class="ml-2 flex flex-col w-full">
            <p class="text-sm font-semibold leading-tight break-words">PT. Sejahtera Indonesia</p>
            <p class="text-xs text-gray-400 leading-tight break-words">sejahteracoorperation@gmail.com</p>
        </div>
    </div>
</div>
  
  
  
   <!-- Content (masih wadah belum ada fitur) -->
   <div class="flex-grow flex flex-col">
    <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
    <h1 class="text-xl font-semibold">All Tickets</h1>

    <div class="flex space-x-2">
        <!-- Tombol Logout -->
            <form action="{{ route('logout') }}" method="POST">
                 @csrf
                <button type="submit" class="bg-red-500 text-white px-2 py-1 text-sm font-semibold rounded">
                    Logout
                </button>
            </form>

            <!-- Tombol New Ticket -->
            <button class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded">
            + New Ticket
            </button>
        </div>
    </div>
  </div>
 </body>
</html>
