<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solved Tickets</title>
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
                    <a href="{{ route('user.pending-ticket') }}" class="menu-item py-2">
                        <i class="fas fa-clock text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Pending</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.solved-ticket') }}" class="menu-item py-2 bg-blue-800">
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
                                        {{ $ticket->due_date ? (is_string($ticket->due_date) ? $ticket->due_date : $ticket->due_date->format('d/m/Y')) : 'N/A' }}
                                    </td>
                                    <td class="p-2">
                                        <button onclick="openTicketModal({{ $ticket->id }})" class="bg-blue-500 text-white px-2 py-1 text-xs rounded hover:bg-blue-600 transition-colors">
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

    <!-- Ticket Details Modal - New Improved Version -->
    <div id="ticketModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-xl p-0 max-w-md w-full transform transition-transform duration-300 scale-95 opacity-0" id="modalContent">
            <!-- Modal Header with colored bar based on priority -->
            <div id="modalHeader" class="rounded-t-lg p-4 text-white relative">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-ticket-alt mr-2"></i>
                        <span id="ticketNumber"></span>
                    </h2>
                    <button onclick="closeTicketModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-1 flex items-center">
                    <span id="priorityBadge" class="text-xs px-2 py-1 rounded-full"></span>
                    <span id="statusBadge" class="ml-2 text-xs px-2 py-1 rounded-full"></span>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <!-- Company Info -->
                <div class="mb-6 border-b border-gray-200 pb-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                        <div>
                            <h3 id="companyName" class="font-semibold text-gray-800"></h3>
                            <p id="companyEmail" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Ticket Details -->
                <div class="space-y-4">
                    <!-- Description -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Description</h4>
                        <p id="description" class="text-gray-800 whitespace-pre-line text-sm"></p>
                    </div>
                    
                    <!-- Asset Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Asset Name</h4>
                            <p id="assetName" class="text-gray-800 text-sm"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Asset Series</h4>
                            <p id="assetSeries" class="text-gray-800 text-sm"></p>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Timeline</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full p-1 mr-2">
                                    <i class="fas fa-calendar-plus text-xs text-blue-600"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700">Created</h5>
                                    <p id="createdDate" class="text-xs text-gray-600"></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-green-100 rounded-full p-1 mr-2">
                                    <i class="fas fa-calendar-check text-xs text-green-600"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700">Resolved</h5>
                                    <p id="resolvedDate" class="text-xs text-gray-600"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resolution Info -->
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-full p-2 mr-3">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Resolution</h4>
                                <p id="resolutionTime" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 p-4 rounded-b-lg border-t border-gray-200">
                <div class="flex justify-end">
                    <button onclick="closeTicketModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors focus:outline-none">
                        Close
                    </button>
                </div>
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
            const modalContent = document.getElementById('modalContent');
            
            // Show modal but content is still invisible
            modal.classList.remove('hidden');
            
            // Fetch ticket details via AJAX 
            fetch(`/tickets/${ticketId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(ticket => {
                    // Set modal header color based on priority
                    const headerColor = getPriorityColor(ticket.priority);
                    document.getElementById('modalHeader').style.backgroundColor = headerColor;
                    
                    // Set ticket details
                    document.getElementById('ticketNumber').textContent = ticket.ticket_number;
                    
                    // Set priority badge
                    const priorityBadge = document.getElementById('priorityBadge');
                    priorityBadge.textContent = capitalizeFirstLetter(ticket.priority);
                    priorityBadge.style.backgroundColor = getPriorityBgColor(ticket.priority);
                    priorityBadge.style.color = getPriorityTextColor(ticket.priority);
                    
                    // Set status badge
                    const statusBadge = document.getElementById('statusBadge');
                    statusBadge.textContent = capitalizeFirstLetter(ticket.status);
                    statusBadge.style.backgroundColor = getStatusBgColor(ticket.status);
                    statusBadge.style.color = getStatusTextColor(ticket.status);
                    
                    // Set company info
                    document.getElementById('companyName').textContent = ticket.company_name;
                    document.getElementById('companyEmail').textContent = ticket.company_email;
                    
                    // Set ticket details
                    document.getElementById('description').textContent = ticket.description;
                    document.getElementById('assetName').textContent = ticket.asset_name || 'N/A';
                    document.getElementById('assetSeries').textContent = ticket.asset_series || 'N/A';
                    
                    // Set timeline
                    document.getElementById('createdDate').textContent = formatDateTime(ticket.created_at);
                    document.getElementById('resolvedDate').textContent = ticket.resolved_date ? formatDateTime(ticket.resolved_date) : 'Not resolved yet';
                    
                    // Set resolution info
                    document.getElementById('resolutionTime').textContent = `Resolved in ${calculateResolutionTime(ticket.created_at, ticket.resolved_date)}`;
                    
                    // Animate modal appearance
                    setTimeout(() => {
                        modalContent.classList.add('scale-100', 'opacity-100');
                        modalContent.classList.remove('scale-95', 'opacity-0');
                    }, 50);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalContent').innerHTML = `
                        <div class="p-6 text-center">
                            <div class="bg-red-100 rounded-full p-4 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-red-600 mb-2">Error Loading Ticket</h3>
                            <p class="text-gray-600 mb-4">Could not load the ticket details. Please try again.</p>
                            <button onclick="closeTicketModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Close
                            </button>
                        </div>
                    `;
                    modalContent.classList.add('scale-100', 'opacity-100');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                });
        }

        function closeTicketModal() {
            const modal = document.getElementById('ticketModal');
            const modalContent = document.getElementById('modalContent');
            
            // Animate modal disappearance
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            
            // Hide modal after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Helper functions
        function calculateResolutionTime(createdAt, resolvedAt) {
            if (!resolvedAt) return 'Not resolved yet';
            
            const created = new Date(createdAt);
            const resolved = new Date(resolvedAt);
            const diffTime = Math.abs(resolved - created);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            const diffHours = Math.floor((diffTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            if (diffDays > 0) {
                return `${diffDays} day${diffDays > 1 ? 's' : ''} and ${diffHours} hour${diffHours > 1 ? 's' : ''}`;
            } else if (diffHours > 0) {
                return `${diffHours} hour${diffHours > 1 ? 's' : ''}`;
            } else {
                const diffMinutes = Math.floor((diffTime % (1000 * 60 * 60)) / (1000 * 60));
                return `${diffMinutes} minute${diffMinutes > 1 ? 's' : ''}`;
            }
        }

        function formatDateTime(dateString) {
            if (!dateString) return 'N/A';
            
            const date = new Date(dateString);
            return date.toLocaleString('en-GB', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        function getPriorityColor(priority) {
            switch (priority.toLowerCase()) {
                case 'low': return '#10B981'; // Green
                case 'medium': return '#F59E0B'; // Amber
                case 'high': return '#EF4444'; // Red
                case 'critical': return '#7F1D1D'; // Dark Red
                default: return '#6B7280'; // Gray
            }
        }
        
        function getPriorityBgColor(priority) {
            switch (priority.toLowerCase()) {
                case 'low': return '#D1FAE5'; // Light Green
                case 'medium': return '#FEF3C7'; // Light Amber
                case 'high': return '#FEE2E2'; // Light Red
                case 'critical': return '#FECACA'; // Lighter Red
                default: return '#F3F4F6'; // Light Gray
            }
        }
        
        function getPriorityTextColor(priority) {
            switch (priority.toLowerCase()) {
                case 'low': return '#065F46'; // Dark Green
                case 'medium': return '#92400E'; // Dark Amber
                case 'high': return '#B91C1C'; // Dark Red
                case 'critical': return '#7F1D1D'; // Darker Red
                default: return '#374151'; // Dark Gray
            }
        }
        
        function getStatusBgColor(status) {
            switch (status.toLowerCase()) {
                case 'open': return '#DBEAFE'; // Light Blue
                case 'pending': return '#FEF3C7'; // Light Amber
                case 'in_progress': return '#E0E7FF'; // Light Indigo
                case 'solved': return '#D1FAE5'; // Light Green
                case 'late': return '#FEE2E2'; // Light Red
                case 'closed': return '#E5E7EB'; // Light Gray
                default: return '#F3F4F6'; // Light Gray
            }
        }
        
        function getStatusTextColor(status) {
            switch (status.toLowerCase()) {
                case 'open': return '#1E40AF'; // Dark Blue
                case 'pending': return '#92400E'; // Dark Amber
                case 'in_progress': return '#4338CA'; // Dark Indigo
                case 'solved': return '#065F46'; // Dark Green
                case 'late': return '#B91C1C'; // Dark Red
                case 'closed': return '#374151'; // Dark Gray
                default: return '#374151'; // Dark Gray
            }
        }
        
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    </script>
</body>
</html>