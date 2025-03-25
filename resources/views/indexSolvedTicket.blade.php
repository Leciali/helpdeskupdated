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
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div id="content" class="content flex-grow flex flex-col">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <button id="openSidebar" class="toggle-button mr-3" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold">Solved Tickets</h1>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
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
                    <!-- Filter and Search -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-2">
                            <select class="border rounded px-2 py-1 text-sm">
                                <option>All Priorities</option>
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                                <option>Critical</option>
                            </select>
                            <input type="text" placeholder="Search tickets..." class="border rounded px-2 py-1 text-sm w-64">
                        </div>
                        <div>
                            <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                                Export <i class="fas fa-file-export ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2">Ticket Number</th>
                                <th class="p-2">Company</th>
                                <th class="p-2">Description</th>
                                <th class="p-2">Priority</th>
                                <th class="p-2">Resolved Date</th>
                                <th class="p-2">Resolution Time</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2">{{ $ticket->ticket_number }}</td>
                                    <td class="p-2">{{ $ticket->company_name }}</td>
                                    <td class="p-2">{{ Str::limit($ticket->description, 50) }}</td>
                                    <td class="p-2">
                                        <span class="
                                            @if($ticket->priority == 'low') text-green-500
                                            @elseif($ticket->priority == 'medium') text-yellow-500
                                            @elseif($ticket->priority == 'high') text-red-500
                                            @else text-purple-500
                                            @endif
                                        ">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td class="p-2">
                                        {{ $ticket->resolved_date ? $ticket->resolved_date->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="p-2">
                                        @php
                                            $resolutionTime = $ticket->start_date && $ticket->resolved_date 
                                                ? $ticket->start_date->diffInDays($ticket->resolved_date) 
                                                : 'N/A';
                                        @endphp
                                        {{ $resolutionTime }} {{ $resolutionTime !== 'N/A' ? 'day(s)' : '' }}
                                    </td>
                                    <td class="p-2">
                                        <button onclick="openTicketModal({{ $ticket->id }})" class="bg-blue-500 text-white px-2 py-1 text-xs rounded hover:bg-blue-600">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center p-4">No solved tickets found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Details Modal -->
    <div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
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
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleSidebar');
        const openBtn = document.getElementById('openSidebar');
        
        function toggleSidebar() {
            if (sidebar.classList.contains('sidebar-collapsed')) {
                sidebar.classList.remove('sidebar-collapsed');
                content.style.marginLeft = '10rem';
                openBtn.style.display = 'none';
            } else {
                sidebar.classList.add('sidebar-collapsed');
                content.style.marginLeft = '0';
                openBtn.style.display = 'flex';
            }
        }
        
        content.style.marginLeft = '10rem';
        
        toggleBtn.addEventListener('click', toggleSidebar);
        openBtn.addEventListener('click', toggleSidebar);

        // Modal Functions
        function openTicketModal(ticketId) {
            const modal = document.getElementById('ticketModal');
            const modalContent = document.getElementById('ticketModalContent');
            
            // Fetch ticket details via AJAX 
            fetch(`/tickets/${ticketId}`)
                .then(response => response.json())
                .then(ticket => {
                    // Calculate resolution time
                    const startDate = new Date(ticket.start_date);
                    const resolvedDate = new Date(ticket.resolved_date);
                    const resolutionTime = ticket.start_date && ticket.resolved_date 
                        ? Math.ceil((resolvedDate - startDate) / (1000 * 60 * 60 * 24)) 
                        : 'N/A';

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
                                        ticket.priority === 'high' ? 'text-red-600' :
                                        'text-purple-600'
                                    }">
                                        ${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}
                                    </p>
                                </div>
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
                                    <p>${new Date(ticket.start_date).toLocaleDateString()}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Resolved Date:</p>
                                    <p>${new Date(ticket.resolved_date).toLocaleDateString()}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Resolution Time:</p>
                                <p>${resolutionTime} ${resolutionTime !== 'N/A' ? 'day(s)' : ''}</p>
                            </div>
                        </div>
                    `;
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