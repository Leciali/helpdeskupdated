<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tickets</title>
    <link rel="icon" type="image/png" href="/asset/logo_pertamina.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <a href="{{ route('user.dashboard') }}" class="menu-item py-2 bg-blue-800">
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
                    <img class="rounded-full h-8 w-8 flex-shrink-0 border border-white" src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('asset/user.png') }}"/>
                    <div class="ml-2 flex flex-col w-full overflow-hidden">
                        <a href="{{ route('profile.edit') }}" class="block text-xs font-semibold leading-tight truncate text-white hover:underline">PT Pertagas Jakarta</a>
                        <p class="text-xs text-blue-200 leading-tight truncate">Admin@pertagas.com</p>
                    </div>
                </div>
            </div>
        </div>
  
        <!-- Content -->
        <div id="content" class="content flex-grow flex flex-col">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <!-- Toggle button yang terlihat seperti hamburger menu -->
                    <button id="openSidebar" class="toggle-button mr-3" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold">All Tickets</h1>
                </div>

                <div class="flex space-x-2">
                    <!-- Tombol New Ticket -->
                    <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
                        <span class="mr-1">+</span> New Ticket
                    </a>
                </div>
            </div>
            
            <!-- Area konten utama -->
            <div class="p-4">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-lg font-semibold mb-3">Daftar Tiket</h2>
                    
                    @if($tickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="p-2 text-center">
                                            <a href="?sort=ticket_number&order={{ request('sort') == 'ticket_number' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                                Ticket Number
                                                @if(request('sort') == 'ticket_number')
                                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fas fa-sort"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="p-2 text-center">
                                            <a href="?sort=company_name&order={{ request('sort') == 'company_name' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                                Company
                                                @if(request('sort') == 'company_name')
                                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fas fa-sort"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="p-2 text-center">
                                            <a href="?sort=description&order={{ request('sort') == 'description' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                                Description
                                                @if(request('sort') == 'description')
                                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fas fa-sort"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="p-2 text-center">
                                            <a href="?sort=status&order={{ request('sort') == 'status' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                                Status
                                                @if(request('sort') == 'status')
                                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fas fa-sort"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="p-2 text-center">
                                            <a href="?sort=priority&order={{ request('sort') == 'priority' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                                Priority
                                                @if(request('sort') == 'priority')
                                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fas fa-sort"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="p-2 text-center">
                                            <a href="?sort=due_date&order={{ request('sort') == 'due_date' && request('order') == 'asc' ? 'desc' : 'asc' }}">
                                                Due Date
                                                @if(request('sort') == 'due_date')
                                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="fas fa-sort"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="p-2 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="p-2">{{ $ticket->ticket_number }}</td>
                                            <td class="p-2">{{ $ticket->company_name }}</td>
                                            <td class="p-2">{{ \Illuminate\Support\Str::limit($ticket->description, 50) }}</td>
                                            <td class="p-2 text-center">
                                                <span class="px-2 py-1 rounded text-xs
                                                    @if($ticket->status == 'open') bg-blue-100 text-blue-800
                                                    @elseif($ticket->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status == 'in_progress') bg-indigo-100 text-indigo-800
                                                    @elseif($ticket->status == 'solved') bg-green-100 text-green-800
                                                    @elseif($ticket->status == 'late') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif
                                                ">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
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
                                            <td class="p-2 text-center">
                                            @if($ticket->due_date)
                                                    <div class="flex items-center space-x-2 justify-center">
                                                <div class="bg-gray-100 text-gray-800 text-sm font-semibold px-3 py-1 rounded shadow-sm countdown" 
                                                    data-due-date="{{ is_string($ticket->due_date) ? $ticket->due_date : $ticket->due_date->format('Y-m-d H:i:s') }}">
                                                    Loading...
                                                </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">N/A</span>
                                            @endif
                                            </td>
                                            <td class="p-2">
                                                <button onclick="openTicketModal({{ $ticket->id }})" class="bg-blue-500 text-white px-2 py-1 text-xs rounded hover:bg-blue-600">
                                                    Details
                                                </button>
                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus tiket ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this)" class="bg-red-500 text-white px-2 py-1 text-xs rounded hover:bg-red-600 ml-2">
                                                        Delete
                                                    </button>
                                                </form>
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
                        <p class="text-center py-4 text-gray-600">Tidak ada tiket yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Details Modal - Compact & Scrollable Version -->
    <div id="ticketModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-xl p-0 max-w-sm w-full max-h-[80vh] transform transition-transform duration-300 scale-95 opacity-0" id="modalContent">
            <!-- Modal Header with colored bar based on priority -->
            <div id="modalHeader" class="rounded-t-lg p-3 text-white relative sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold flex items-center">
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
            
            <!-- Scrollable Modal Body -->
            <div class="overflow-y-auto p-4" style="max-height: calc(80vh - 130px);">
                <!-- Company Info -->
                <div class="mb-4 border-b border-gray-200 pb-3">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-2 mr-2 flex-shrink-0">
                            <i class="fas fa-building text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <h3 id="companyName" class="font-semibold text-gray-800 text-sm"></h3>
                            <p id="companyEmail" class="text-xs text-gray-600"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Ticket Details -->
                <div class="space-y-3">
                    <!-- Description -->
                    <div>
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Description</h4>
                        <p id="description" class="text-gray-800 whitespace-pre-line text-sm"></p>
                    </div>
                    
                    <!-- Asset Info -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Asset Name</h4>
                            <p id="assetName" class="text-gray-800 text-sm"></p>
                        </div>
                        <div>
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Asset Series</h4>
                            <p id="assetSeries" class="text-gray-800 text-sm"></p>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <div>
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Timeline</h4>
                        <div class="grid grid-cols-1 gap-2 mt-1">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full p-1 mr-2 flex-shrink-0">
                                    <i class="fas fa-calendar-plus text-xs text-blue-600"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700">Created</h5>
                                    <p id="createdDate" class="text-xs text-gray-600"></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-yellow-100 rounded-full p-1 mr-2 flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-xs text-yellow-600"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700">Due By</h5>
                                    <p id="dueDate" class="text-xs text-gray-600"></p>
                                </div>
                            </div>
                            <div id="resolvedDateContainer" class="flex items-center">
                                <div class="bg-green-100 rounded-full p-1 mr-2 flex-shrink-0">
                                    <i class="fas fa-calendar-check text-xs text-green-600"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-medium text-gray-700">Resolved</h5>
                                    <p id="resolvedDate" class="text-xs text-gray-600"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ticket Progress -->
                    <div class="bg-gray-50 p-2 rounded">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Ticket Status</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                            <div id="progressBar" class="h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Created</span>
                            <span id="progressStatus">Pending</span>
                            <span>Complete</span>
                        </div>
                    </div>
                    
                    <!-- Duration Info -->
                    <div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-1 mr-2 flex-shrink-0">
                                <i class="fas fa-clock text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-medium text-gray-700">Duration</h4>
                                <p id="ticketDuration" class="text-xs text-gray-600"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer with Status Update Form -->
            <div class="bg-gray-50 p-3 rounded-b-lg border-t border-gray-200 sticky bottom-0">
                <form id="statusUpdateForm" method="POST" class="flex space-x-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="border border-gray-300 rounded px-2 py-1 text-xs flex-grow focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="solved">Solved</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
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
                    document.getElementById('dueDate').textContent = ticket.due_date ? formatDateTime(ticket.due_date) : 'Not set';
                    
                    // Show/hide resolved date based on status
                    const resolvedDateContainer = document.getElementById('resolvedDateContainer');
                    if (ticket.resolved_date) {
                        document.getElementById('resolvedDate').textContent = formatDateTime(ticket.resolved_date);
                        resolvedDateContainer.classList.remove('hidden');
                    } else {
                        resolvedDateContainer.classList.add('hidden');
                    }
                    
                    // Set ticket duration
                    document.getElementById('ticketDuration').textContent = `${ticket.ticket_duration} day${ticket.ticket_duration !== 1 ? 's' : ''}`;
                    
                    // Update progress bar
                    updateProgressBar(ticket.status);
                    
                    // Update status form
                    const statusForm = document.getElementById('statusUpdateForm');
                    statusForm.action = `/tickets/${ticket.id}/status`;
                    const statusSelect = statusForm.querySelector('select[name="status"]');
                    statusSelect.value = ticket.status;
                    
                    // Animate modal appearance
                    setTimeout(() => {
                        modalContent.classList.add('scale-100', 'opacity-100');
                        modalContent.classList.remove('scale-95', 'opacity-0');
                    }, 50);
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalBody = document.querySelector('#modalContent > div:nth-child(2)');
                    modalBody.innerHTML = `
                        <div class="p-4 text-center">
                            <div class="bg-red-100 rounded-full p-3 mx-auto w-12 h-12 flex items-center justify-center mb-3">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-red-600 mb-1">Error Loading Ticket</h3>
                            <p class="text-xs text-gray-600 mb-2">Could not load the ticket details.</p>
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
        function updateProgressBar(status) {
            const progressBar = document.getElementById('progressBar');
            const progressStatus = document.getElementById('progressStatus');
            
            switch(status.toLowerCase()) {
                case 'open':
                    progressBar.style.width = '10%';
                    progressBar.style.backgroundColor = '#3B82F6'; // blue
                    progressStatus.textContent = 'Open';
                    break;
                case 'pending':
                    progressBar.style.width = '30%';
                    progressBar.style.backgroundColor = '#F59E0B'; // amber
                    progressStatus.textContent = 'Pending';
                    break;
                case 'in_progress':
                    progressBar.style.width = '60%';
                    progressBar.style.backgroundColor = '#6366F1'; // indigo
                    progressStatus.textContent = 'In Progress';
                    break;
                case 'solved':
                    progressBar.style.width = '100%';
                    progressBar.style.backgroundColor = '#10B981'; // green
                    progressStatus.textContent = 'Solved';
                    break;
                case 'late':
                    progressBar.style.width = '50%';
                    progressBar.style.backgroundColor = '#EF4444'; // red
                    progressStatus.textContent = 'Late';
                    break;
                default:
                    progressBar.style.width = '10%';
                    progressBar.style.backgroundColor = '#6B7280'; // gray
                    progressStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);
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
        
        function formatCountdown(diff) {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((diff / (1000 * 60)) % 60);
            const seconds = Math.floor((diff / 1000) % 60);

            if (diff <= 0) return '<span class="text-red-600 font-bold">Expired</span>';

            return `
            ${days > 0 ? `<span class="text-blue-600">${days}d</span> ` : ''}
            <span>${String(hours).padStart(2, '0')}h</span> :
            <span>${String(minutes).padStart(2, '0')}m</span> :
            <span>${String(seconds).padStart(2, '0')}s</span>
            `;
        }

        function updateCountdown() {
            const elements = document.querySelectorAll('.countdown');
            elements.forEach(el => {
            const dueDate = new Date(el.dataset.dueDate);
            const now = new Date();
            const diff = dueDate - now;
            el.innerHTML = formatCountdown(diff);
            });
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        function confirmDelete(btn) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cari form terdekat dan submit
                    btn.closest('form').submit();
                }
            });
        }
    </script>
</body>
</html>