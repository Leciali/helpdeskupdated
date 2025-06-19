<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Ticket Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.0/apexcharts.min.js"></script>
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

        /* Custom styles for report page */
        .metric-card {
          transition: all 0.3s ease;
        }
        
        .metric-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .tab-active {
          border-bottom: 2px solid #0056b3;
        }

        /* Timeline style */
        .timeline-container {
          position: relative;
        }
        
        .timeline-line {
          position: absolute;
          left: 15px;
          top: 0;
          bottom: 0;
          width: 2px;
          background-color: #e5e7eb;
          z-index: 1;
        }
        
        .timeline-item {
          position: relative;
          z-index: 2;
        }
        
        .timeline-icon {
          position: absolute;
          left: 7px;
          top: 0;
          width: 18px;
          height: 18px;
          border-radius: 50%;
          background-color: white;
          display: flex;
          align-items: center;
          justify-content: center;
        }
        
        /* Custom loader */
        .loader {
          border: 3px solid #f3f3f3;
          border-radius: 50%;
          border-top: 3px solid #0056b3;
          width: 24px;
          height: 24px;
          animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50">
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
                        <p class="text-xs font-semibold leading-tight truncate text-white">PT Pertagas Jakarta</p>
                        <p class="text-xs text-blue-200 leading-tight truncate">Admin@pertagas.com</p>
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
                    <h1 class="text-xl font-semibold">Advanced Ticket Analytics</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Date range selector -->
                    <div class="flex items-center">
                        <select id="dateRange" class="text-sm border border-gray-300 rounded px-2 py-1" onchange="window.location='?range='+this.value">
                            <option value="7" {{ (isset($range) && $range==7) ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ (isset($range) && $range==30) ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ (isset($range) && $range==90) ? 'selected' : '' }}>Last 90 days</option>
                            <option value="180" {{ (isset($range) && $range==180) ? 'selected' : '' }}>Last 180 days</option>
                            <option value="365" {{ (isset($range) && $range==365) ? 'selected' : '' }}>Last year</option>
                        </select>
                    </div>
                    
                    <!-- Export buttons -->
                    <div class="flex space-x-1">
                        <a href='/cetak-pdf' class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 text-xs font-medium rounded flex items-center">
                            <i class="fas fa-file-pdf mr-1 text-red-500"></i> PDF
                        </a>
                        <a href='/cetak-excel' class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 text-xs font-medium rounded flex items-center">
                            <i class="fas fa-file-excel mr-1 text-green-600"></i> Excel
                        </a>
                    </div>
                    
                    <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
                        <span class="mr-1">+</span> New Ticket
                    </a>
                </div>
            </div>

            <!-- Dashboard Stats & Charts -->
            <div class="p-4 space-y-4 overflow-y-auto">
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Total Tickets -->
                    <div class="bg-white p-4 rounded-lg shadow-sm metric-card border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Total Tickets</p>
                                <div class="flex items-end">
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        {{ $statistics['total_tickets'] ?? 0 }}
                                    </h3>
                                    <span class="ml-1 text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>8.2%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">vs previous period</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-ticket-alt text-blue-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Open Tickets -->
                    <div class="bg-white p-4 rounded-lg shadow-sm metric-card border-l-4 border-blue-400">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Open Tickets</p>
                                <div class="flex items-end">
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        {{ $statistics['open_tickets'] ?? 0 }}
                                    </h3>
                                    <span class="ml-1 text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>4.3%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">vs previous period</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-folder-open text-blue-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Tickets -->
                    <div class="bg-white p-4 rounded-lg shadow-sm metric-card border-l-4 border-yellow-400">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Pending Tickets</p>
                                <div class="flex items-end">
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        {{ $statistics['pending_tickets'] ?? 0 }}
                                    </h3>
                                    <span class="ml-1 text-xs text-red-500 flex items-center">
                                        <i class="fas fa-arrow-down mr-1"></i>2.1%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">vs previous period</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-yellow-50 flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Solved Tickets -->
                    <div class="bg-white p-4 rounded-lg shadow-sm metric-card border-l-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Solved Tickets</p>
                                <div class="flex items-end">
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        {{ $statistics['solved_tickets'] ?? 0 }}
                                    </h3>
                                    <span class="ml-1 text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>12.7%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">vs previous period</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-green-50 flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Late Tickets -->
                    <div class="bg-white p-4 rounded-lg shadow-sm metric-card border-l-4 border-red-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Late Tickets</p>
                                <div class="flex items-end">
                                    <h3 class="text-2xl font-bold text-gray-800">
                                        {{ $statistics['late_tickets'] ?? 0 }}
                                    </h3>
                                    <span class="ml-1 text-xs text-red-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>3.5%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">vs previous period</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-red-50 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Average Resolution Time -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-800">Avg. Resolution Time</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                KPI
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold text-gray-800">18.5h</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-green-500 text-xs flex items-center">
                                        <i class="fas fa-arrow-down mr-1"></i>2.3h
                                    </span>
                                    <span class="text-xs text-gray-500 ml-1">vs target</span>
                                </div>
                            </div>
                            <div id="resolution-time-chart" class="h-16 w-24"></div>
                        </div>
                    </div>
                    
                    <!-- First Response Time -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-800">First Response Time</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                KPI
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold text-gray-800">1.2h</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-red-500 text-xs flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>0.2h
                                    </span>
                                    <span class="text-xs text-gray-500 ml-1">vs target</span>
                                </div>
                            </div>
                            <div id="response-time-chart" class="h-16 w-24"></div>
                        </div>
                    </div>
                    
                    <!-- SLA Compliance -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-800">SLA Compliance</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                KPI
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold text-gray-800">94.7%</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-green-500 text-xs flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>1.2%
                                    </span>
                                    <span class="text-xs text-gray-500 ml-1">vs target</span>
                                </div>
                            </div>
                            <div id="sla-compliance-chart" class="h-16 w-24"></div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="bg-white rounded-lg shadow-sm p-1">
                    <div class="flex border-b">
                        <button id="tab-overview" class="tab-button py-2 px-4 font-medium text-sm flex-1 text-center tab-active">Overview</button>
                        <button id="tab-trends" class="tab-button py-2 px-4 font-medium text-sm flex-1 text-center">Trend Analysis</button>
                        <button id="tab-priority" class="tab-button py-2 px-4 font-medium text-sm flex-1 text-center">Priority Analysis</button>
                        <button id="tab-assets" class="tab-button py-2 px-4 font-medium text-sm flex-1 text-center">Asset Analysis</button>
                    </div>
                    
                    <!-- Tab Content Containers -->
                    <div id="tab-content-overview" class="tab-content p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Donut Chart for Ticket Distribution -->
                            <div class="bg-white rounded-lg border p-4">
                                <h2 class="text-base font-semibold mb-4">Ticket Distribution by Priority</h2>
                                <div class="chart-container h-60">
                                    <canvas id="ticketPriorityChart"></canvas>
                                </div>
                            </div>

                            <!-- Weekly Trend Chart -->
                            <div class="bg-white rounded-lg border p-4 lg:col-span-2">
                                <h2 class="text-base font-semibold mb-4">Weekly Ticket Trend</h2>
                                <div class="chart-container h-60">
                                    <canvas id="weeklyTicketTrend"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity & Top Asset Issues -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <!-- Recent Activity Timeline -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Recent Activity</h2>
                                    <a href="#" class="text-blue-500 text-xs">View All</a>
                                </div>
                                
                                <div class="timeline-container pl-8 space-y-4">
                                    <div class="timeline-line"></div>
                                    @foreach(
                                        $recentTickets as $ticket)
                                    <div class="timeline-item pb-4">
                                        <div class="timeline-icon">
                                                <i class="fas fa-plus-circle text-blue-500 text-xs"></i>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                    <h4 class="text-sm font-medium">
                                                        {{ $ticket->status == 'solved' ? 'Ticket Resolved' : ($ticket->status == 'pending' ? 'Status Update' : 'New Ticket Created') }}
                                                    </h4>
                                                    <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                    <span class="font-medium">{{ $ticket->ticket_number }}</span> - {{ $ticket->title }}
                                                </p>
                                                @if($ticket->title)
                                                <p class="text-xs text-gray-700 mt-1">
                                                    <span class="font-semibold">Title:</span> {{ $ticket->title }}
                                            </p>
                                                @endif
                                            <div class="flex items-center mt-2">
                                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Asset: {{ $ticket->asset_name }}</span>
                                                    <span class="text-xs bg-{{ $ticket->priority == 'critical' ? 'red' : ($ticket->priority == 'medium' ? 'yellow' : ($ticket->priority == 'high' ? 'orange' : 'green')) }}-100 text-{{ $ticket->priority == 'critical' ? 'red' : ($ticket->priority == 'medium' ? 'yellow' : ($ticket->priority == 'high' ? 'orange' : 'green')) }}-800 px-2 py-0.5 rounded ml-2">{{ ucfirst($ticket->priority) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Top Asset Issues -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Top Asset Issues</h2>
                                    <a href="#" class="text-blue-500 text-xs">View Details</a>
                                </div>
                                
                                <div class="space-y-4">
                                    <!-- Asset 1 -->
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between">
                                            <h4 class="text-sm font-medium">Server-DC1</h4>
                                            <span class="text-xs text-red-500 font-medium">12 issues</span>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                              <div class="bg-red-500 h-2 rounded-full" style="width: 75%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-500">75%</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Critical: 4</span>
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium: 6</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Avg. resolution: 8.2h</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Asset 2 -->
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between">
                                            <h4 class="text-sm font-medium">Network Switch-B2</h4>
                                            <span class="text-xs text-orange-500 font-medium">8 issues</span>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                              <div class="bg-orange-500 h-2 rounded-full" style="width: 55%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-500">55%</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Critical: 2</span>
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium: 5</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Avg. resolution: 6.5h</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Asset 3 -->
                                    <div class="border-b pb-3">
                                        <div class="flex justify-between">
                                            <h4 class="text-sm font-medium">CAD Workstation-A5</h4>
                                            <span class="text-xs text-yellow-500 font-medium">6 issues</span>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                              <div class="bg-yellow-500 h-2 rounded-full" style="width: 40%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-500">40%</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Critical: 1</span>
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium: 3</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Avg. resolution: 4.8h</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Asset 4 -->
                                    <div>
                                        <div class="flex justify-between">
                                            <h4 class="text-sm font-medium">Database-SQL1</h4>
                                            <span class="text-xs text-green-500 font-medium">4 issues</span>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                              <div class="bg-green-500 h-2 rounded-full" style="width: 25%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-gray-500">25%</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Critical: 0</span>
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium: 2</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Avg. resolution: 3.2h</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Content: Trend Analysis -->
                    <div id="tab-content-trends" class="tab-content p-4 hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Monthly Trends -->
                            <div class="bg-white rounded-lg border p-4">
                                <h2 class="text-base font-semibold mb-4">Monthly Ticket Volume</h2>
                                <div class="chart-container h-72">
                                    <canvas id="monthlyTrendsChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Resolution Time Trends -->
                            <div class="bg-white rounded-lg border p-4">
                                <h2 class="text-base font-semibold mb-4">Resolution Time Trends</h2>
                                <div class="chart-container h-72">
                                    <canvas id="resolutionTrendsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Heatmap & Weekday Distribution -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <!-- Ticket Creation Heatmap -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Ticket Creation Heatmap</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>Last 30 days</option>
                                        <option>Last 90 days</option>
                                        <option>Last 180 days</option>
                                    </select>
                                </div>
                                <div id="ticket-heatmap" class="h-72"></div>
                            </div>
                            
                            <!-- Day of Week Distribution -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Tickets by Day of Week</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>Last 30 days</option>
                                        <option>Last 90 days</option>
                                        <option>Last 180 days</option>
                                    </select>
                                </div>
                                <div class="chart-container h-72">
                                    <canvas id="weekdayDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Content: Priority Analysis -->
                    <div id="tab-content-priority" class="tab-content p-4 hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Priority Distribution -->
                            <div class="bg-white rounded-lg border p-4">
                                <h2 class="text-base font-semibold mb-4">Priority Distribution</h2>
                                <div class="chart-container h-60">
                                    <canvas id="priorityDistributionChart"></canvas>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    <div class="bg-gray-50 p-2 rounded">
                                        <p class="text-xs text-gray-500">Critical</p>
                                        <div class="flex justify-between items-center">
                                            <p class="text-lg font-semibold">{{ $statistics['tickets_by_priority']['critical'] ?? 0 }}</p>
                                            <span class="text-xs px-2 py-0.5 bg-red-100 text-red-700 rounded">
                                                @if(($statistics['total_tickets'] ?? 0) > 0)
                                                    {{ number_format(($statistics['tickets_by_priority']['critical'] / $statistics['total_tickets']) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded">
                                        <p class="text-xs text-gray-500">High</p>
                                        <div class="flex justify-between items-center">
                                            <p class="text-lg font-semibold">{{ $statistics['tickets_by_priority']['high'] ?? 0 }}</p>
                                            <span class="text-xs px-2 py-0.5 bg-orange-100 text-orange-700 rounded">
                                                @if(($statistics['total_tickets'] ?? 0) > 0)
                                                    {{ number_format(($statistics['tickets_by_priority']['high'] / $statistics['total_tickets']) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded">
                                        <p class="text-xs text-gray-500">Medium</p>
                                        <div class="flex justify-between items-center">
                                            <p class="text-lg font-semibold">{{ $statistics['tickets_by_priority']['medium'] ?? 0 }}</p>
                                            <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded">
                                                @if(($statistics['total_tickets'] ?? 0) > 0)
                                                    {{ number_format(($statistics['tickets_by_priority']['medium'] / $statistics['total_tickets']) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded">
                                        <p class="text-xs text-gray-500">Low</p>
                                        <div class="flex justify-between items-center">
                                            <p class="text-lg font-semibold">{{ $statistics['tickets_by_priority']['low'] ?? 0 }}</p>
                                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded">
                                                @if(($statistics['total_tickets'] ?? 0) > 0)
                                                    {{ number_format(($statistics['tickets_by_priority']['low'] / $statistics['total_tickets']) * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Resolution Time by Priority -->
                            <div class="bg-white rounded-lg border p-4 lg:col-span-2">
                                <h2 class="text-base font-semibold mb-4">Resolution Time by Priority</h2>
                                <div class="chart-container h-60">
                                    <canvas id="resolutionByPriorityChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Priority over Time & SLA Compliance -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <!-- Priority Distribution Over Time -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Priority Distribution Over Time</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>Monthly</option>
                                        <option>Quarterly</option>
                                        <option>Yearly</option>
                                    </select>
                                </div>
                                <div class="chart-container h-72">
                                    <canvas id="priorityTimeChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- SLA Compliance by Priority -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">SLA Compliance by Priority</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>Last 30 days</option>
                                        <option>Last 90 days</option>
                                        <option>Last 180 days</option>
                                    </select>
                                </div>
                                <div class="chart-container h-72">
                                    <canvas id="slaComplianceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Content: Asset Analysis -->
                    <div id="tab-content-assets" class="tab-content p-4 hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Top Assets by Ticket Volume -->
                            <div class="bg-white rounded-lg border p-4">
                                <h2 class="text-base font-semibold mb-4">Top Assets by Ticket Volume</h2>
                                <div class="chart-container h-72">
                                    <canvas id="topAssetsChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Asset Category Distribution -->
                            <div class="bg-white rounded-lg border p-4 lg:col-span-2">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Asset Category Distribution</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>All Time</option>
                                        <option>This Year</option>
                                        <option>Last 90 days</option>
                                    </select>
                                </div>
                                <div class="chart-container h-72">
                                    <canvas id="assetCategoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Asset Health & Recurring Issues -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <!-- Asset Health Index -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Asset Health Index</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>Top 10 Assets</option>
                                        <option>Critical Assets</option>
                                        <option>All Assets</option>
                                    </select>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Health Score</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issues</th>
                                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="py-2 px-3 text-sm">Server-DC1</td>
                                                <td class="py-2 px-3">
                                                    <div class="flex items-center">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-red-500 h-2 rounded-full" style="width: 35%"></div>
                                                        </div>
                                                        <span class="text-xs">35%</span>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-3 text-sm">12</td>
                                                <td class="py-2 px-3">
                                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Critical</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3 text-sm">Network Switch-B2</td>
                                                <td class="py-2 px-3">
                                                    <div class="flex items-center">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 65%"></div>
                                                        </div>
                                                        <span class="text-xs">65%</span>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-3 text-sm">8</td>
                                                <td class="py-2 px-3">
                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Warning</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3 text-sm">CAD Workstation-A5</td>
                                                <td class="py-2 px-3">
                                                    <div class="flex items-center">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 72%"></div>
                                                        </div>
                                                        <span class="text-xs">72%</span>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-3 text-sm">6</td>
                                                <td class="py-2 px-3">
                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Warning</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3 text-sm">Database-SQL1</td>
                                                <td class="py-2 px-3">
                                                    <div class="flex items-center">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                                                        </div>
                                                        <span class="text-xs">85%</span>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-3 text-sm">4</td>
                                                <td class="py-2 px-3">
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Healthy</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3 text-sm">Printer-HR5</td>
                                                <td class="py-2 px-3">
                                                    <div class="flex items-center">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                                                        </div>
                                                        <span class="text-xs">92%</span>
                                                    </div>
                                                </td>
                                                <td class="py-2 px-3 text-sm">2</td>
                                                <td class="py-2 px-3">
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Healthy</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Recurring Issues -->
                            <div class="bg-white rounded-lg border p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-base font-semibold">Recurring Issues by Asset</h2>
                                    <select class="text-xs border border-gray-300 rounded p-1">
                                        <option>Last 90 days</option>
                                        <option>Last 180 days</option>
                                        <option>All Time</option>
                                    </select>
                                </div>
                                <div class="space-y-4">
                                    <!-- Recurring Issue 1 -->
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-sm font-medium">Network Connectivity Issues</h4>
                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">14 occurrences</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Affects: <span class="font-medium">Network Switch-B2, Router-B23</span>
                                        </p>
                                        <div class="mt-2">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span>Resolution rate</span>
                                                <span>78%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 78%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Recurring Issue 2 -->
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-sm font-medium">Server Overload</h4>
                                            <span class="text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded">9 occurrences</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Affects: <span class="font-medium">Server-DC1, Database-SQL1</span>
                                        </p>
                                        <div class="mt-2">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span>Resolution rate</span>
                                                <span>65%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 65%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Recurring Issue 3 -->
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-sm font-medium">Software License Expiration</h4>
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">7 occurrences</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Affects: <span class="font-medium">CAD Software, Design Suite-A3</span>
                                        </p>
                                        <div class="mt-2">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span>Resolution rate</span>
                                                <span>92%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 92%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Load more data section -->
                <div class="flex justify-center mt-4">
                    <button class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-2 px-4 rounded flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Load More Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
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

        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                tabButtons.forEach(btn => btn.classList.remove('tab-active'));
                // Add active class to clicked button
                button.classList.add('tab-active');
                
                // Hide all tab contents
                tabContents.forEach(content => content.classList.add('hidden'));
                
                // Show the selected tab content
                const tabId = button.id.replace('tab-', 'tab-content-');
                document.getElementById(tabId).classList.remove('hidden');
                
                // Initialize or update charts for the newly visible tab
                if (tabId === 'tab-content-trends') {
                    initTrendsCharts();
                } else if (tabId === 'tab-content-priority') {
                    initPriorityCharts();
                } else if (tabId === 'tab-content-assets') {
                    initAssetCharts();
                }
            });
        });

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // KPI sparkline charts
            initKPICharts();
            
            // Overview tab charts (shown by default)
            setupTicketPriorityChart();
            setupWeeklyTicketTrend();
        });
        
        // KPI Charts initialization
        function initKPICharts() {
            // Resolution Time Chart
            const resolutionTimeOptions = {
                chart: {
                    type: 'line',
                    height: 60,
                    sparkline: { enabled: true },
                    animations: { enabled: false }
                },
                series: [{
                    name: 'Resolution Time',
                    data: [22, 24, 20, 19, 18, 17, 18.5]
                }],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#3b82f6'],
                tooltip: {
                    fixed: { enabled: false },
                    x: { show: false },
                    y: {
                        title: {
                            formatter: function() {
                                return 'Hours';
                            }
                        }
                    },
                    marker: { show: false }
                }
            };
            
            const responseTimeOptions = {
                chart: {
                    type: 'line',
                    height: 60,
                    sparkline: { enabled: true },
                    animations: { enabled: false }
                },
                series: [{
                    name: 'First Response',
                    data: [1.5, 1.4, 1.3, 1.1, 1.2, 1.3, 1.2]
                }],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#f59e0b'],
                tooltip: {
                    fixed: { enabled: false },
                    x: { show: false },
                    y: {
                        title: {
                            formatter: function() {
                                return 'Hours';
                            }
                        }
                    },
                    marker: { show: false }
                }
            };
            
            const slaComplianceOptions = {
                chart: {
                    type: 'line',
                    height: 60,
                    sparkline: { enabled: true },
                    animations: { enabled: false }
                },
                series: [{
                    name: 'SLA Compliance',
                    data: [91, 92, 93.5, 94.2, 93.8, 94.5, 94.7]
                }],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#10b981'],
                tooltip: {
                    fixed: { enabled: false },
                    x: { show: false },
                    y: {
                        title: {
                            formatter: function() {
                                return '%';
                            }
                        }
                    },
                    marker: { show: false }
                }
            };
            
            new ApexCharts(document.querySelector("#resolution-time-chart"), resolutionTimeOptions).render();
            new ApexCharts(document.querySelector("#response-time-chart"), responseTimeOptions).render();
            new ApexCharts(document.querySelector("#sla-compliance-chart"), slaComplianceOptions).render();
        }

        // Ticket Priority Distribution Chart
        function setupTicketPriorityChart() {
            const ctx = document.getElementById('ticketPriorityChart').getContext('2d');
            
            const priorityData = {
                low: {{ $statistics['tickets_by_priority']['low'] ?? 0 }},
                medium: {{ $statistics['tickets_by_priority']['medium'] ?? 0 }},
                high: {{ $statistics['tickets_by_priority']['high'] ?? 0 }},
                critical: {{ $statistics['tickets_by_priority']['critical'] ?? 0 }}
            };

            // If all values are zero, set some default values to prevent chart errors
            const total = Object.values(priorityData).reduce((a, b) => a + b, 0);
            if (total === 0) {
                priorityData.low = 1;
                priorityData.medium = 1;
                priorityData.high = 1;
                priorityData.critical = 1;
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Low', 'Medium', 'High', 'Critical'],
                    datasets: [{
                        data: [
                            priorityData.low, 
                            priorityData.medium, 
                            priorityData.high, 
                            priorityData.critical
                        ],
                        backgroundColor: [
                            '#2e7d32',  // green for low
                            '#ff8f00',  // amber for medium
                            '#ff5722',  // deep orange for high
                            '#c62828',  // red for critical
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = Object.values(priorityData).reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Weekly Ticket Trend Chart
        function setupWeeklyTicketTrend() {
            const ctx = document.getElementById('weeklyTicketTrend').getContext('2d');

            const weeklyTrend = {!! json_encode($statistics['weekly_trend'] ?? []) !!};

            const labels = Object.keys(weeklyTrend);
            const openData = labels.map(date => weeklyTrend[date].open);
            const solvedData = labels.map(date => weeklyTrend[date].solved);
            const pendingData = labels.map(date => weeklyTrend[date].pending);
            const inProgressData = labels.map(date => weeklyTrend[date].in_progress);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Open Tickets',
                            data: openData,
                            borderColor: '#1976d2',
                            backgroundColor: 'rgba(25, 118, 210, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Pending Tickets',
                            data: pendingData,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'In Progress Tickets',
                            data: inProgressData,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Solved Tickets',
                            data: solvedData,
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
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        }
        
        // Trends Tab Charts
        function initTrendsCharts() {
            // Monthly Trends Chart
            const monthlyCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Created',
                            data: [45, 52, 38, 60, 55, 58, 62, 65, 68, 72, 78, 85],
                            backgroundColor: '#1976d2',
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Resolved',
                            data: [40, 48, 36, 52, 51, 54, 59, 60, 62, 68, 74, 80],
                            backgroundColor: '#2e7d32',
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
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
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
            
            // Resolution Time Trends Chart
            const resolutionTimeCtx = document.getElementById('resolutionTrendsChart').getContext('2d');
            new Chart(resolutionTimeCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Avg. Resolution Time (hours)',
                            data: [24, 22, 25, 21, 20, 19, 18, 17.5, 17, 18, 18.5, 18.2],
                            borderColor: '#1976d2',
                            backgroundColor: 'rgba(25, 118, 210, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Target',
                            data: [20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20],
                            borderColor: '#f44336',
                            borderDash: [5, 5],
                            borderWidth: 2,
                            pointRadius: 0,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
            
            // Weekday Distribution Chart
            const weekdayCtx = document.getElementById('weekdayDistributionChart').getContext('2d');
            new Chart(weekdayCtx, {
                type: 'bar',
                data: {
                    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    datasets: [
                        {
                            label: 'Created Tickets',
                            data: [32, 28, 25, 24, 20, 12, 8],
                            backgroundColor: '#1976d2',
                            barPercentage: 0.7
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
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Ticket Creation Heatmap
            const heatmapOptions = {
                series: [{
                    name: 'Tickets Created',
                    data: generateHeatmapData()
                }],
                chart: {
                    height: 280,
                    type: 'heatmap',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    heatmap: {
                        radius: 3,
                        enableShades: true,
                        shadeIntensity: 0.5,
                        colorScale: {
                            ranges: [
                                {
                                    from: 0,
                                    to: 5,
                                    color: '#BBDEFB',
                                    name: 'Low'
                                },
                                {
                                    from: 6,
                                    to: 10,
                                    color: '#64B5F6',
                                    name: 'Medium'
                                },
                                {
                                    from: 11,
                                    to: 15,
                                    color: '#2196F3',
                                    name: 'High'
                                },
                                {
                                    from: 16,
                                    to: 50,
                                    color: '#1565C0',
                                    name: 'Very High'
                                }
                            ]
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    type: 'category',
                    categories: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00']
                },
                title: {
                    text: 'Ticket Creation by Time and Day',
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'normal'
                    }
                },
                grid: {
                    padding: {
                        right: 10
                    }
                }
            };
            
            new ApexCharts(document.querySelector("#ticket-heatmap"), heatmapOptions).render();
        }
        
        // Helper function to generate heatmap data
        function generateHeatmapData() {
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            let data = [];
            
            days.forEach(day => {
                // Business hours (8-18) have higher values
                data.push({ x: day, y: Math.floor(Math.random() * 5) }); // 00:00
                data.push({ x: day, y: Math.floor(Math.random() * 3) }); // 04:00
                data.push({ x: day, y: Math.floor(Math.random() * 5) + 8 }); // 08:00
                data.push({ x: day, y: Math.floor(Math.random() * 10) + 10 }); // 12:00
                data.push({ x: day, y: Math.floor(Math.random() * 10) + 5 }); // 16:00
                data.push({ x: day, y: Math.floor(Math.random() * 5) }); // 20:00
            });
            
            return data;
        }
        
        // Priority Tab Charts
        function initPriorityCharts() {
            // Priority Distribution Chart
            const priorityCtx = document.getElementById('priorityDistributionChart').getContext('2d');
            new Chart(priorityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Low', 'Medium', 'High', 'Critical'],
                    datasets: [{
                        data: [
                            {{ $statistics['tickets_by_priority']['low'] ?? 0 }}, 
                            {{ $statistics['tickets_by_priority']['medium'] ?? 0 }}, 
                            {{ $statistics['tickets_by_priority']['high'] ?? 0 }}, 
                            {{ $statistics['tickets_by_priority']['critical'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#2e7d32',  // green for low
                            '#ff8f00',  // amber for medium
                            '#ff5722',  // deep orange for high
                            '#c62828',  // red for critical
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Resolution Time by Priority Chart
            const resolutionByPriorityCtx = document.getElementById('resolutionByPriorityChart').getContext('2d');
            new Chart(resolutionByPriorityCtx, {
                type: 'bar',
                data: {
                    labels: ['Critical', 'High', 'Medium', 'Low'],
                    datasets: [
                        {
                            label: 'Avg. Resolution Time (hours)',
                            data: [8.5, 12.3, 18.4, 24.6],
                            backgroundColor: ['#c62828', '#ff5722', '#ff8f00', '#2e7d32'],
                            barPercentage: 0.6
                        },
                        {
                            label: 'Target Time (hours)',
                            data: [8, 16, 24, 36],
                            type: 'line',
                            borderColor: '#1976d2',
                            borderDash: [5, 5],
                            borderWidth: 2,
                            pointStyle: 'circle',
                            pointRadius: 5,
                            pointBorderColor: '#1976d2',
                            pointBackgroundColor: '#fff',
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Hours'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
            
            // Priority Distribution Over Time Chart
            const priorityTimeCtx = document.getElementById('priorityTimeChart').getContext('2d');
            new Chart(priorityTimeCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Critical',
                            data: [5, 6, 4, 8, 7, 9, 8, 10, 9, 12, 14, 15],
                            backgroundColor: '#c62828',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'High',
                            data: [10, 12, 8, 14, 12, 13, 15, 16, 18, 20, 22, 25],
                            backgroundColor: '#ff5722',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Medium',
                            data: [18, 20, 16, 24, 22, 20, 24, 24, 26, 25, 26, 28],
                            backgroundColor: '#ff8f00',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Low',
                            data: [12, 14, 10, 14, 14, 16, 15, 15, 15, 15, 16, 17],
                            backgroundColor: '#2e7d32',
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
            
            // SLA Compliance by Priority Chart
            const slaComplianceCtx = document.getElementById('slaComplianceChart').getContext('2d');
            new Chart(slaComplianceCtx, {
                type: 'radar',
                data: {
                    labels: ['Critical', 'High', 'Medium', 'Low', 'Overall'],
                    datasets: [
                        {
                            label: 'Current SLA Compliance',
                            data: [92.5, 94.1, 96.3, 98.2, 94.7],
                            backgroundColor: 'rgba(25, 118, 210, 0.2)',
                            borderColor: '#1976d2',
                            borderWidth: 2,
                            pointBackgroundColor: '#1976d2'
                        },
                        {
                            label: 'Target',
                            data: [95, 95, 95, 95, 95],
                            backgroundColor: 'rgba(244, 67, 54, 0.1)',
                            borderColor: '#f44336',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointBackgroundColor: '#f44336'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 85,
                            suggestedMax: 100,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }
        
        // Asset Analysis Tab Charts
        function initAssetCharts() {
            // Top Assets by Ticket Volume Chart
            const topAssetsCtx = document.getElementById('topAssetsChart').getContext('2d');
            new Chart(topAssetsCtx, {
                type: 'bar',
                data: {
                    labels: ['Server-DC1', 'Network Switch-B2', 'CAD Workstation-A5', 'Database-SQL1', 'Printer-HR5'],
                    datasets: [
                        {
                            label: 'Number of Tickets',
                            data: [12, 8, 6, 4, 2],
                            backgroundColor: '#1976d2',
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Asset Category Distribution Chart
            const assetCategoryCtx = document.getElementById('assetCategoryChart').getContext('2d');
            new Chart(assetCategoryCtx, {
                type: 'bar',
                data: {
                    labels: ['Hardware', 'Network', 'Server', 'Software', 'Peripheral', 'Other'],
                    datasets: [
                        {
                            label: 'Open Tickets',
                            data: [15, 18, 22, 12, 8, 5],
                            backgroundColor: '#1976d2',
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Resolved Tickets',
                            data: [32, 28, 45, 25, 12, 8],
                            backgroundColor: '#2e7d32',
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
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
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>