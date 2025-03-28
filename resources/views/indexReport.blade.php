<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
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

        /* Tab styling */
        .tab-active {
          color: #0078d4;
          border-bottom: 2px solid #0078d4;
        }

        /* Chart styling */
        .chart-container {
          height: 200px;
          position: relative;
        }
        .bar {
          position: absolute;
          bottom: 40px;
          width: 24px;
          background-color: #0078d4;
          border-radius: 4px 4px 0 0;
        }
        .bar-label {
          position: absolute;
          bottom: 20px;
          font-size: 12px;
          text-align: center;
          width: 24px;
        }
        .bar-value {
          position: absolute;
          bottom: 100%;
          font-size: 12px;
          text-align: center;
          width: 24px;
          margin-bottom: 4px;
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
                        <i class="fas fa-chart-bar text-white text-sm"></i>
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
                    <!-- Toggle button yang terlihat seperti hamburger menu -->
                    <button id="openSidebar" class="toggle-button mr-3" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold">Report</h1>
                </div>
                <div class="flex space-x-2">
                    <!-- Tombol New Ticket -->
                    <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
                        <span class="mr-1">+</span> New Ticket
                    </a>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 bg-white">
                <div class="flex px-6">
                    <button class="py-4 px-4 font-medium text-sm tab-active">Resume Bulanan</button>
                    <button class="py-4 px-4 font-medium text-sm text-gray-500">Resume Personal</button>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-4">
                <!-- Ticket Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- All Tickets Card -->
                    <div class="bg-blue-600 text-white rounded-lg shadow-md p-6 flex flex-col justify-center items-center">
                        <p class="text-lg font-medium mb-2">All Admission Tickets</p>
                        <p class="text-4xl font-bold">129</p>
                    </div>
                    
                    <!-- Open Tickets Card -->
                    <div class="bg-gray-200 text-gray-800 rounded-lg shadow-md p-6 flex flex-col justify-center items-center">
                        <p class="text-lg font-medium mb-2">Open Ticket</p>
                        <p class="text-4xl font-bold">50</p>
                    </div>
                    
                    <!-- Closed Tickets Card -->
                    <div class="bg-green-500 text-white rounded-lg shadow-md p-6 flex flex-col justify-center items-center">
                        <p class="text-lg font-medium mb-2">Closed Ticket</p>
                        <p class="text-4xl font-bold">43</p>
                    </div>
                    
                    <!-- Waiting Tickets Card -->
                    <div class="bg-yellow-400 text-gray-800 rounded-lg shadow-md p-6 flex flex-col justify-center items-center">
                        <p class="text-lg font-medium mb-2">Waiting Tickets</p>
                        <p class="text-4xl font-bold">36</p>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bar Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">All Month Pass every 10 days</h3>
                        <div class="chart-container mt-8">
                            <div class="relative w-full" style="height: 220px;">
                                <!-- Bar 1 -->
                                <div class="bar" style="height: 160px; left: 7%;"></div>
                                <div class="bar-value" style="left: 7%;">32</div>
                                <div class="bar-label" style="left: 7%;">1</div>
                                
                                <!-- Bar 2 -->
                                <div class="bar" style="height: 60px; left: 16%;"></div>
                                <div class="bar-value" style="left: 16%;">12</div>
                                <div class="bar-label" style="left: 16%;">2</div>
                                
                                <!-- Bar 3 -->
                                <div class="bar" style="height: 115px; left: 25%;"></div>
                                <div class="bar-value" style="left: 25%;">23</div>
                                <div class="bar-label" style="left: 25%;">3</div>
                                
                                <!-- Bar 4 -->
                                <div class="bar" style="height: 70px; left: 34%;"></div>
                                <div class="bar-value" style="left: 34%;">14</div>
                                <div class="bar-label" style="left: 34%;">4</div>
                                
                                <!-- Bar 5 -->
                                <div class="bar" style="height: 115px; left: 43%;"></div>
                                <div class="bar-value" style="left: 43%;">23</div>
                                <div class="bar-label" style="left: 43%;">5</div>
                                
                                <!-- Bar 6 -->
                                <div class="bar" style="height: 125px; left: 52%;"></div>
                                <div class="bar-value" style="left: 52%;">25</div>
                                <div class="bar-label" style="left: 52%;">6</div>
                                
                                <!-- Bar 7 -->
                                <div class="bar" style="height: 105px; left: 61%;"></div>
                                <div class="bar-value" style="left: 61%;">21</div>
                                <div class="bar-label" style="left: 61%;">7</div>
                                
                                <!-- Bar 8 -->
                                <div class="bar" style="height: 105px; left: 70%;"></div>
                                <div class="bar-value" style="left: 70%;">21</div>
                                <div class="bar-label" style="left: 70%;">8</div>
                                
                                <!-- Bar 9 -->
                                <div class="bar" style="height: 105px; left: 79%;"></div>
                                <div class="bar-value" style="left: 79%;">21</div>
                                <div class="bar-label" style="left: 79%;">9</div>
                                
                                <!-- Bar 10 -->
                                <div class="bar" style="height: 105px; left: 88%;"></div>
                                <div class="bar-value" style="left: 88%;">21</div>
                                <div class="bar-label" style="left: 88%;">10</div>
                                
                                <!-- X-axis label -->
                                <div class="absolute bottom-0 left-0 w-full text-center text-sm text-gray-500 mt-4">
                                    Days
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donut Chart -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Total Tickets a Month by Category</h3>
                        <div class="flex justify-center items-center h-40">
                            <!-- Simple donut chart representation -->
                            <div class="relative w-40 h-40">
                                <!-- Using a simple representation of the donut chart -->
                                <svg viewBox="0 0 100 100" class="w-full h-full">
                                    <circle cx="50" cy="50" r="40" fill="#90caf9" />
                                    <circle cx="50" cy="50" r="30" fill="white" />
                                    <!-- Center text -->
                                    <text x="50" y="55" text-anchor="middle" font-size="16" font-weight="bold">129</text>
                                </svg>
                                
                                <!-- Donut segments would normally be created with actual data -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                                        <span class="text-2xl font-bold">129</span>
                                    </div>
                                </div>
                            </div>
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
    </script>
</body>
</html>