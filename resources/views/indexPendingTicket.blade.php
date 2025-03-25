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
                <div class="bg-white shadow-md rounded-lg p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2">Ticket Number</th>
                                <th class="p-2">Company</th>
                                <th class="p-2">Description</th>
                                <th class="p-2">Resolved Date</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-t">
                                    <td class="p-2">{{ $ticket->ticket_number }}</td>
                                    <td class="p-2">{{ $ticket->company_name }}</td>
                                    <td class="p-2">{{ Str::limit($ticket->description, 50) }}</td>
                                    <td class="p-2">
                                        {{ $ticket->resolved_date ? $ticket->resolved_date->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="p-2">
                                        <button onclick="openTicketModal({{ $ticket->id }})" class="bg-blue-500 text-white px-2 py-1 text-xs rounded">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4">No solved tickets found</td>
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
    <div id="ticketModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Ticket Details</h2>
                <button onclick="closeTicketModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Content will be dynamically populated -->
            <div id="ticketModalContent"></div>
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
                    modalContent.innerHTML = `
                        <div class="space-y-2">
                            <p><strong>Ticket Number:</strong> ${ticket.ticket_number}</p>
                            <p><strong>Company:</strong> ${ticket.company_name}</p>
                            <p><strong>Email:</strong> ${ticket.company_email}</p>
                            <p><strong>Description:</strong> ${ticket.description}</p>
                            <p><strong>Asset Name:</strong> ${ticket.asset_name || 'N/A'}</p>
                            <p><strong>Asset Series:</strong> ${ticket.asset_series || 'N/A'}</p>
                            <p><strong>Priority:</strong> ${ticket.priority}</p>
                            <p><strong>Resolved Date:</strong> ${new Date(ticket.resolved_date).toLocaleDateString()}</p>
                            <p><strong>Total Resolution Time:</strong> ${calculateResolutionTime(ticket.created_at, ticket.resolved_date)}</p>
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

        // Helper function to calculate resolution time
        function calculateResolutionTime(createdAt, resolvedAt) {
            const created = new Date(createdAt);
            const resolved = new Date(resolvedAt);
            const diffTime = Math.abs(resolved - created);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            return `${diffDays} day(s)`;
        }
    </script>
</body>
</html>