<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-gray-300 w-40 flex flex-col h-screen">
            <div class="flex justify-center items-center py-4 border-b border-gray-700">
                <img src="asset/LogoPertamina.png" class="h-8">
            </div>

            {{-- <div class="flex justify-center mt-3 relative">
                <i class="fas fa-bell text-xs text-gray-400"></i>
                <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] rounded-full px-1">
                    99+
                </span>
            </div> --}}

            <ul class="mt-4 space-y-1">
                <li class="flex items-center px-3 py-1.5 hover:bg-gray-800 rounded cursor-pointer">
                  <i class="fas fa-users text-xs text-gray-400"></i>
                  <a href="{{ route('user.dashboard') }}"  class="ml-2 text-sm font-light">All Tickets</a>
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
                  <a href="{{ route('user.solved-ticket') }}"class="ml-2 text-sm font-light">Solved</a>
                </li>
                <li class="flex items-center px-3 py-1.5 hover:bg-red-900 rounded cursor-pointer">
                  <i class="fas fa-times text-xs text-gray-400"></i>
                  <a href="{{ route('user.report') }}" class="ml-2 text-sm font-light">Report</a>
                </li>
              </ul>

            <div class="mt-auto flex items-center justify-center h-12 border-t border-gray-700">
                <img class="rounded-full h-8 w-8" src="asset/user.png"/>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col h-screen overflow-auto">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold">Create a New Ticket</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 text-sm font-semibold rounded">
                        Logout
                    </button>
                </form>
            </div>
            <div class="p-6">
                <form class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-auto">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Company</label>
                        <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea class="mt-1 block w-full p-2 border border-gray-300 rounded-md h-24"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Priority</label>
                        <select class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Submit Ticket</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
