<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solved Tickets</title>
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
        </div>
        
        <!-- Main Content -->
        <div class="flex-grow flex flex-col">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold">Solved Tickets</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 text-sm font-semibold rounded">
                        Logout
                    </button>
                </form>
            </div>
            <div class="p-4">
                <table class="w-full bg-white rounded shadow">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                            <th class="py-2 px-4 text-left">Ticket</th>
                            <th class="py-2 px-4 text-left">Solved Date</th>
                            <th class="py-2 px-4 text-left">Priority</th>
                            <th class="py-2 px-4 text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2 px-4">L001</td>
                            <td class="py-2 px-4">20/02/2025</td>
                            <td class="py-2 px-4 text-green-600">Low</td>
                            <td class="py-2 px-4 text-center">
                                <button onclick="openModal()" class="bg-blue-500 text-white px-3 py-1 rounded">View</button>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 px-4">H002</td>
                            <td class="py-2 px-4">10/01/2025</td>
                            <td class="py-2 px-4 text-red-500">High</td>
                            <td class="py-2 px-4 text-center">
                                <button onclick="openModal()" class="bg-blue-500 text-white px-3 py-1 rounded">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
   <!-- Modal -->
<div id="modal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg w-96 max-h-[80vh] overflow-auto">
        <h2 class="text-xl font-semibold mb-2">Ticket Details</h2>
        <p><strong>Asset:</strong> Printer</p>
        <p><strong>Seri:</strong> JKDSA213389</p>
        <p><strong>Resolved Date:</strong> 20/02/2025</p>
        <p><strong>Email:</strong> sejahteracoorp@gmail.com</p>
        <p><strong>Company Name:</strong> PT. Sejahtera</p>
        <p><strong>Description:</strong> Printer mengalami error pada tinta</p>

        <div class="mt-4 text-right">
            <button onclick="closeModal()" class="bg-red-500 text-white px-3 py-1 rounded">Close</button>
        </div>
    </div>
</div>


    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</body>
</html>
