<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Tickets Dashboard
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
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
   @include('layouts.sidebar')
  
   <!-- Content (masih wadah belum ada fitur) -->
   <div id="content" class="content flex-grow flex flex-col">
    <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
      <div class="flex items-center">
        <!-- Toggle button yang terlihat seperti hamburger menu -->
        <button id="openSidebar" class="toggle-button mr-3">
          <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-xl font-semibold">All Tickets</h1>
      </div>
            
      <div class="flex space-x-2">
        <!-- Tombol New Ticket jika diperlukan -->
          <a href="{{ route('user.open-ticket') }}" class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded flex items-center">
              <span class="mr-1">+</span> New Ticket
          </a>
      </div>
      
    </div>
    <!-- Area konten utama -->
    <div class="p-4">
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-3">Daftar Tiket</h2>
        <p>Konten dashboard Anda akan muncul di sini.</p>
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