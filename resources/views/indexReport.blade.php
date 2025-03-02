<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Tickets</title>
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
            <ul class="mt-4 space-y-1">
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

        <!-- Content -->
        <div class="flex-grow flex flex-col">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold">Report</h1>
                <div class="flex space-x-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 text-sm font-semibold rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="p-4">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2">#</th>
                                <th class="p-2">Subject</th>
                                <th class="p-2">Status</th>
                                <th class="p-2">Priority</th>
                                <th class="p-2">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="p-2">1</td>
                                <td class="p-2">Printer</td>
                                <td class="p-2 text-green-600">Solved</td>
                                <td class="p-2 text-red-500">High</td>
                                <td class="p-2">
                                    <button class="bg-blue-500 text-white px-2 py-1 text-xs rounded">View</button>
                                </td>
                            </tr>
                            <tr class="border-t">
                                <td class="p-2">2</td>
                                <td class="p-2">Laptop ASUS</td>
                                <td class="p-2 text-green-600">Solved</td>
                                <td class="p-2 text-yellow-500">Medium</td>
                                <td class="p-2">
                                    <button class="bg-blue-500 text-white px-2 py-1 text-xs rounded">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
