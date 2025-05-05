<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Tickets</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        /* CSS untuk animasi sidebar */
        .sidebar {
          transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
          position: fixed;
          z-index: 40;
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .sidebar-collapsed {
          transform: translateX(-100%);
        }
        
        .content {
          transition: margin-left 0.3s ease-in-out;
        }
        
        .toggle-button {
          width: 28px;
          height: 28px;
          border-radius: 50%;
          background-color: rgba(0, 77, 153, 0.8);
          color: white;
          display: flex;
          align-items: center;
          justify-content: center;
          border: none;
          cursor: pointer;
          box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
          transition: all 0.2s ease;
        }
        
        .toggle-button:hover {
          background-color: rgba(0, 61, 122, 1);
        }
        
        .menu-item {
          display: flex;
          align-items: center;
          padding: 0.5rem 0.75rem;
          border-radius: 0.25rem;
          transition: all 0.2s ease;
        }
        
        .menu-item:hover {
          background-color: rgba(255, 255, 255, 0.1);
        }
        
        .menu-item i {
          width: 18px;
          text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar text-white w-40 flex flex-col h-screen" style="background-color: #0056b3;">
            <div class="flex justify-between items-center py-4 px-3 border-b border-blue-900" style="background-color: #003d7a;">
                <div class="flex items-center">
                    <img src="asset/LogoPertamina.png" class="h-7">
                </div>
                <button id="toggleSidebar" class="toggle-button flex items-center justify-center">
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
            </div>

            <!-- LIST MENU (Gunakan flex-grow agar profil terdorong ke bawah) -->
            <ul class="mt-2 space-y-0 flex-grow px-2">
                <li>
                    <a href="{{ route('user.dashboard') }}" class="menu-item py-2">
                        <i class="fas fa-ticket-alt text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">All Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.open-ticket') }}" class="menu-item py-2">
                        <i class="fas fa-folder-open text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Open</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.pending-ticket') }}" class="menu-item py-2 bg-blue-800">
                        <i class="fas fa-clock text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Pending</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.solved-ticket') }}" class="menu-item py-2">
                        <i class="fas fa-check text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Solved</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.report') }}" class="menu-item py-2">
                        <i class="fas fa-chart-pie text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Report</span>
                    </a>
                </li>
            </ul>

            <!-- Tombol Logout dan profil -->
            <div class="mt-auto">
                <!-- Tombol Logout -->
                <div class="px-3 py-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-1 bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded transition duration-200 text-sm">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
                
                <!-- PROFIL -->
                <div class="flex items-center px-3 py-3 border-t border-blue-900" style="background-color: #003d7a;">
                    <img class="rounded-full h-8 w-8 flex-shrink-0 border border-white" src="asset/user.png"/>
                    <div class="ml-2 flex flex-col w-full overflow-hidden">
                        <p class="text-xs font-semibold leading-tight truncate text-white">PT. Sejahtera Indonesia</p>
                        <p class="text-xs text-blue-200 leading-tight truncate">sejahteracoorperation@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div id="content" class="content flex-grow flex flex-col">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <button id="openSidebar" class="toggle-button mr-3" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold">Pending Tickets</h1>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('tickets.create') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
                        <span class="mr-1">+</span> New Ticket
                    </a>
                </div>
            </div>

            <div class="p-4">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white shadow-md rounded-lg p-4">
                    @if($tickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="p-2">Ticket Number</th>
                                        <th class="p-2">Company</th>
                                        <th class="p-2">Description</th>
                                        <th class="p-2">Priority</th>
                                        <th class="p-2">Due Date</th>
                                        <th class="p-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="p-2">{{ $ticket->ticket_number }}</td>
                                            <td class="p-2">{{ $ticket->company_name }}</td>
                                            <td class="p-2">{{ \Illuminate\Support\Str::limit($ticket->description, 50) }}</td>
                                            <td class="p-2">
                                                <span class="
                                                    @if($ticket->priority == 'low') text-green-600
                                                    @elseif($ticket->priority == 'medium') text-yellow-600
                                                    @elseif($ticket->priority == 'high') text-orange-600
                                                    @else text-red-600
                                                    @endif
                                                ">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td class="p-2">
                                                {{ $ticket->due_date ? $ticket->due_date->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="p-2">
                                                <button onclick="openTicketModal({{ $ticket->id }})" class="bg-blue-500 text-white px-2 py-1 text-xs rounded hover:bg-blue-600">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-600">
                            <p>No pending tickets found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Details Modal -->
    <div id="ticketModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Ticket Details</h2>
                <button onclick="closeTicketModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="ticketModalContent">
                <!-- Content will be dynamically populated -->
            </div>

            <!-- Status Update Form -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h3 class="font-medium mb-2">Update Status</h3>
                <form id="statusUpdateForm" method="POST" class="flex space-x-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="border rounded px-2 py-1 text-sm flex-grow">
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="solved">Solved</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle script
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleSidebar');
        const openBtn = document.getElementById('openSidebar');
        
        function toggleSidebar() {
            if (sidebar.classList.contains('sidebar-collapsed')) {
                // Membuka sidebar
                sidebar.classList.remove('sidebar-collapsed');
                content.style.marginLeft = '10rem';
                openBtn.style.display = 'none';
            } else {
                // Menutup sidebar
                sidebar.classList.add('sidebar-collapsed');
                content.style.marginLeft = '0';
                openBtn.style.display = 'flex';
            }
        }
        
        // Set initial state - sidebar terbuka
        content.style.marginLeft = '10rem';
        
        // Menambahkan event listener ke tombol
        toggleBtn.addEventListener('click', toggleSidebar);
        openBtn.addEventListener('click', toggleSidebar);

        // Modal Functions
        function openTicketModal(ticketId) {
            const modal = document.getElementById('ticketModal');
            const modalContent = document.getElementById('ticketModalContent');
            const statusForm = document.getElementById('statusUpdateForm');
            
            // Fetch ticket details via AJAX 
            fetch(`/tickets/${ticketId}`)
                .then(response => response.json())
                .then(ticket => {
                    // Update modal content
                    modalContent.innerHTML = `
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-sm text-gray-600">Ticket Number:</p>
                                    <p class="font-semibold">${ticket.ticket_number}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Priority:</p>
                                    <p class="font-semibold ${
                                        ticket.priority === 'low' ? 'text-green-600' :
                                        ticket.priority === 'medium' ? 'text-yellow-600' :
                                        ticket.priority === 'high' ? 'text-orange-600' :
                                        'text-red-600'
                                    }">
                                        ${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Status:</p>
                                <p class="font-semibold">
                                    <span class="px-2 py-1 rounded text-xs ${
                                        ticket.status === 'open' ? 'bg-blue-100 text-blue-800' :
                                        ticket.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                        ticket.status === 'in_progress' ? 'bg-indigo-100 text-indigo-800' :
                                        ticket.status === 'solved' ? 'bg-green-100 text-green-800' :
                                        ticket.status === 'late' ? 'bg-red-100 text-red-800' :
                                        'bg-gray-100 text-gray-800'
                                    }">
                                        ${ticket.status.charAt(0).toUpperCase() + ticket.status.slice(1)}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Company:</p>
                                <p class="font-semibold">${ticket.company_name}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Company Email:</p>
                                <p>${ticket.company_email}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Asset Name:</p>
                                <p>${ticket.asset_name || 'N/A'}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Asset Series:</p>
                                <p>${ticket.asset_series || 'N/A'}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Description:</p>
                                <p>${ticket.description}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-sm text-gray-600">Start Date:</p>
                                    <p>${ticket.start_date ? new Date(ticket.start_date).toLocaleDateString() : 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Due Date:</p>
                                    <p>${ticket.due_date ? new Date(ticket.due_date).toLocaleDateString() : 'N/A'}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Ticket Duration:</p>
                                <p>${ticket.ticket_duration} day(s)</p>
                            </div>

                            ${ticket.resolved_date ? `
                            <div>
                                <p class="text-sm text-gray-600">Resolved Date:</p>
                                <p>${new Date(ticket.resolved_date).toLocaleDateString()}</p>
                            </div>
                            ` : ''}
                        </div>
                    `;

                    // Update status form action and select current status
                    statusForm.action = `/tickets/${ticket.id}/status`;
                    const statusSelect = statusForm.querySelector('select[name="status"]');
                    statusSelect.value = ticket.status;
                    
                    // Show modal
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load ticket details');
                });
        }

        function closeTicketModal() {
            const modal = document.getElementById('ticketModal');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>