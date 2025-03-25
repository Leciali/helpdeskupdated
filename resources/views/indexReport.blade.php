<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h1 class="text-xl font-semibold">Ticket Reports & Analytics</h1>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
                        <span class="mr-1">+</span> New Ticket
                    </a>
                </div>
            </div>

            <!-- Dashboard Stats & Charts -->
            <div class="p-4 space-y-4 overflow-y-auto">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Open Tickets -->
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Open Tickets</p>
                                <h3 class="text-2xl font-bold text-blue-600">
                                    {{ $statistics['open_tickets'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-folder-open text-blue-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Tickets -->
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Pending Tickets</p>
                                <h3 class="text-2xl font-bold text-yellow-500">
                                    {{ $statistics['pending_tickets'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Solved Tickets -->
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Solved Tickets</p>
                                <h3 class="text-2xl font-bold text-green-600">
                                    {{ $statistics['solved_tickets'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Late Tickets -->
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Late Tickets</p>
                                <h3 class="text-2xl font-bold text-red-600">
                                    {{ $statistics['late_tickets'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Donut Chart for Ticket Distribution -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h2 class="text-lg font-semibold mb-4">Ticket Distribution by Priority</h2>
                        <div class="chart-container h-60">
                            <canvas id="ticketPriorityChart"></canvas>
                        </div>
                    </div>

                    <!-- Weekly Trend Chart -->
                    <div class="bg-white rounded-lg shadow-md p-4 lg:col-span-2">
                        <h2 class="text-lg font-semibold mb-4">Weekly Ticket Trend</h2>
                        <div class="chart-container h-60">
                            <canvas id="weeklyTicketTrend"></canvas>
                        </div>
                    </div>
                </div>
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

        // Ticket Priority Distribution Chart
        function setupTicketPriorityChart() {
            const ctx = document.getElementById('ticketPriorityChart').getContext('2d');
            
            const priorityData = {
                low: {{ $statistics['tickets_by_priority']['low'] ?? 0 }},
                medium: {{ $statistics['tickets_by_priority']['medium'] ?? 0 }},
                high: {{ $statistics['tickets_by_priority']['high'] ?? 0 }},
                critical: {{ $statistics['tickets_by_priority']['critical'] ?? 0 }}};

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

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
setupTicketPriorityChart();
setupWeeklyTicketTrend();
});
</script>
</body>
</html>