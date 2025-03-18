<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <!-- Chart.js untuk membuat donut chart -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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

        /* Status badge styling */
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        .status-open {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-pending {
            background-color: #fff8e1;
            color: #ff8f00;
        }
        .status-solved {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .status-late {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Priority badge styling */
        .priority-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        .priority-low {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .priority-medium {
            background-color: #fff8e1;
            color: #ff8f00;
        }
        .priority-high {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Tab styles */
        .tab-button {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab-button:hover {
            background-color: #f1f5f9;
        }
        .tab-button.active {
            background-color: #0056b3;
            color: white;
        }

        /* Chart container */
        .chart-container {
            position: relative;
            height: 240px;
            width: 100%;
        }

        /* Stats card */
        .stats-card {
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
                    <a href="{{ route('user.solved-ticket') }}" class="menu-item py-2">
                        <i class="fas fa-check text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Solved</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.report') }}" class="menu-item py-2 bg-blue-800">
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
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <!-- Toggle button yang terlihat seperti hamburger menu -->
                    <button id="openSidebar" class="toggle-button mr-3" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold">Ticket Reports & Analytics</h1>
                </div>
                <div class="flex space-x-2">
                    <!-- Tombol New Ticket -->
                    <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm font-semibold rounded flex items-center transition duration-200">
                        <i class="fas fa-plus-circle mr-2"></i> New Ticket
                    </a>
                </div>
            </div>

            <!-- Dashboard Stats & Charts -->
            <div class="p-4 space-y-4 overflow-y-auto">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Open Tickets -->
                    <div class="stats-card bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Open Tickets</p>
                                <h3 class="text-2xl font-bold text-blue-600">12</h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-folder-open text-blue-500"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <span class="text-green-500"><i class="fas fa-arrow-up"></i> 8%</span> from last week
                        </p>
                    </div>
                    
                    <!-- Pending Tickets -->
                    <div class="stats-card bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Pending Tickets</p>
                                <h3 class="text-2xl font-bold text-yellow-500">7</h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-500"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <span class="text-red-500"><i class="fas fa-arrow-down"></i> 5%</span> from last week
                        </p>
                    </div>
                    
                    <!-- Solved Tickets -->
                    <div class="stats-card bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Solved Tickets</p>
                                <h3 class="text-2xl font-bold text-green-600">42</h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <span class="text-green-500"><i class="fas fa-arrow-up"></i> 12%</span> from last week
                        </p>
                    </div>
                    
                    <!-- Late Tickets -->
                    <div class="stats-card bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Late Tickets</p>
                                <h3 class="text-2xl font-bold text-red-600">5</h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <span class="text-green-500"><i class="fas fa-arrow-down"></i> 3%</span> from last week
                        </p>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Donut Chart -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h2 class="text-lg font-semibold mb-4">Ticket Distribution</h2>
                        <div class="chart-container">
                            <canvas id="ticketDonutChart"></canvas>
                        </div>
                    </div>

                    <!-- Weekly Trend Chart -->
                    <div class="bg-white rounded-lg shadow-md p-4 lg:col-span-2">
                        <h2 class="text-lg font-semibold mb-4">Weekly Ticket Trend</h2>
                        <div class="chart-container">
                            <canvas id="ticketTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Ticket Listings with Tabs -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                        <h2 class="text-lg font-semibold">Ticket Details</h2>
                        <div class="flex space-x-2 overflow-x-auto">
                            <button class="tab-button active" data-status="all">All</button>
                            <button class="tab-button" data-status="open">Open</button>
                            <button class="tab-button" data-status="pending">Pending</button>
                            <button class="tab-button" data-status="solved">Solved</button>
                            <button class="tab-button" data-status="late">Late</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="p-3 text-sm font-medium text-gray-500">#</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Subject</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Status</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Priority</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Department</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Created</th>
                                    <th class="p-3 text-sm font-medium text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ticketTableBody">
                                <!-- Tickets will be populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <p class="text-sm text-gray-600">Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">66</span> results</p>
                        <div class="flex space-x-1">
                            <button class="px-3 py-1 bg-gray-200 rounded-md text-sm">Previous</button>
                            <button class="px-3 py-1 bg-blue-500 text-white rounded-md text-sm">1</button>
                            <button class="px-3 py-1 bg-gray-200 rounded-md text-sm">2</button>
                            <button class="px-3 py-1 bg-gray-200 rounded-md text-sm">3</button>
                            <button class="px-3 py-1 bg-gray-200 rounded-md text-sm">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mengambil elemen yang dibutuhkan
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleSidebar');
        const openBtn = document.getElementById('openSidebar');
        
        // Toggle sidebar function
        function toggleSidebar() {
          if (sidebar.classList.contains('sidebar-collapsed')) {
            // Membuka sidebar
            sidebar.classList.remove('sidebar-collapsed');
            content.style.marginLeft = '10rem'; // w-40 = 10rem
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

        // Sample data for tickets
        const tickets = [
            { id: 1, subject: "Printer not working", status: "open", priority: "high", department: "IT Support", created: "2025-03-10" },
            { id: 2, subject: "Software installation request", status: "pending", priority: "medium", department: "IT Support", created: "2025-03-09" },
            { id: 3, subject: "Network connectivity issue", status: "solved", priority: "high", department: "Network", created: "2025-03-08" },
            { id: 4, subject: "Email not syncing", status: "open", priority: "medium", department: "IT Support", created: "2025-03-08" },
            { id: 5, subject: "Password reset request", status: "solved", priority: "low", department: "IT Support", created: "2025-03-07" },
            { id: 6, subject: "Server maintenance", status: "pending", priority: "high", department: "Server Admin", created: "2025-03-07" },
            { id: 7, subject: "Office 365 license issue", status: "solved", priority: "medium", department: "Licensing", created: "2025-03-06" },
            { id: 8, subject: "Database connection error", status: "late", priority: "high", department: "Database", created: "2025-03-04" },
            { id: 9, subject: "Laptop ASUS hardware issue", status: "late", priority: "high", department: "Hardware", created: "2025-03-03" },
            { id: 10, subject: "Video conference setup", status: "solved", priority: "medium", department: "IT Support", created: "2025-03-01" },
        ];

        // Populate table with tickets
        function populateTickets(status = 'all') {
            const tableBody = document.getElementById('ticketTableBody');
            tableBody.innerHTML = '';
            
            const filteredTickets = status === 'all' ? 
                tickets : 
                tickets.filter(ticket => ticket.status === status);
            
            filteredTickets.forEach(ticket => {
                const row = document.createElement('tr');
                row.className = 'border-t hover:bg-gray-50';
                
                // Status badge class
                let statusClass = '';
                switch(ticket.status) {
                    case 'open':
                        statusClass = 'status-open';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        break;
                    case 'solved':
                        statusClass = 'status-solved';
                        break;
                    case 'late':
                        statusClass = 'status-late';
                        break;
                }
                
                // Priority badge class
                let priorityClass = '';
                switch(ticket.priority) {
                    case 'low':
                        priorityClass = 'priority-low';
                        break;
                    case 'medium':
                        priorityClass = 'priority-medium';
                        break;
                    case 'high':
                        priorityClass = 'priority-high';
                        break;
                }
                
                row.innerHTML = `
                    <td class="p-3 text-sm text-gray-700">#${ticket.id}</td>
                    <td class="p-3 text-sm text-gray-700">${ticket.subject}</td>
                    <td class="p-3">
                        <span class="status-badge ${statusClass}">${ticket.status.charAt(0).toUpperCase() + ticket.status.slice(1)}</span>
                    </td>
                    <td class="p-3">
                        <span class="priority-badge ${priorityClass}">${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}</span>
                    </td>
                    <td class="p-3 text-sm text-gray-700">${ticket.department}</td>
                    <td class="p-3 text-sm text-gray-700">${ticket.created}</td>
                    <td class="p-3">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-xs rounded transition duration-200">View</button>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
            
            // Update tab active state
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.status === status) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }

        // Initialize tab functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                populateTickets(button.dataset.status);
            });
        });

        // Initialize table
        populateTickets();

        // Setup donut chart
        function setupDonutChart() {
            const counts = {
                open: tickets.filter(t => t.status === 'open').length,
                pending: tickets.filter(t => t.status === 'pending').length,
                solved: tickets.filter(t => t.status === 'solved').length,
                late: tickets.filter(t => t.status === 'late').length
            };
            
            const ctx = document.getElementById('ticketDonutChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Open', 'Pending', 'Solved', 'Late'],
                    datasets: [{
                        data: [counts.open, counts.pending, counts.solved, counts.late],
                        backgroundColor: [
                            '#1976d2',  // blue for open
                            '#ff8f00',  // amber for pending
                            '#2e7d32',  // green for solved
                            '#c62828',  // red for late
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Setup trend chart
        function setupTrendChart() {
            const ctx = document.getElementById('ticketTrendChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Open',
                            data: [3, 5, 2, 6, 4, 2, 3],
                            borderColor: '#1976d2',
                            backgroundColor: 'rgba(25, 118, 210, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Solved',
                            data: [7, 4, 6, 8, 9, 5, 7],
                            borderColor: '#2e7d32',
                            backgroundColor: 'rgba(46, 125, 50, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            setupDonutChart();
            setupTrendChart();
        });
    </script>
</body>
</html>