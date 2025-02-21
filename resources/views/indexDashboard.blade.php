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
 </head>
 <body class="bg-gray-100">
  <div class="flex h-screen">
  <!-- SideBar -->
<div class="bg-gray-900 text-gray-300 w-40 flex flex-col h-screen">
    <!-- Logo Pertamina (Sementara) -->
    <div class="flex justify-center items-center py-4 border-b border-gray-700">
      <img src="asset/LogoPertamina.png" class="h-8">
    </div>
  
    <!-- Notifikasi (masih gambaran dulu, belum ada fitur nya) -->
    <div class="flex justify-center mt-3 relative">
      <i class="fas fa-bell text-xs text-gray-400"></i>
      <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] rounded-full px-1">
        99+
      </span>
    </div>
  
    <!-- Menu (Sementara) -->
    <ul class="mt-4 space-y-1">
      <li class="flex items-center px-3 py-1.5 hover:bg-gray-800 rounded cursor-pointer">
        <i class="fas fa-users text-xs text-gray-400"></i>
        <span class="ml-2 text-sm font-light">All Tickets</span>
      </li>
      <li class="flex items-center px-3 py-1.5 hover:bg-blue-900 rounded cursor-pointer">
        <i class="fas fa-folder-open text-xs text-gray-400"></i>
        <span class="ml-2 text-sm font-light">Open</span>
      </li>
      <li class="flex items-center px-3 py-1.5 hover:bg-yellow-900 rounded cursor-pointer">
        <i class="fas fa-clock text-xs text-gray-400"></i>
        <span class="ml-2 text-sm font-light">Pending</span>
      </li>
      <li class="flex items-center px-3 py-1.5 hover:bg-green-900 rounded cursor-pointer">
        <i class="fas fa-check text-xs"></i>
        <span class="ml-2 text-sm font-light">Solved</span>
      </li>
      <li class="flex items-center px-3 py-1.5 hover:bg-red-900 rounded cursor-pointer">
        <i class="fas fa-times text-xs text-gray-400"></i>
        <span class="ml-2 text-sm font-light">Closed</span>
      </li>
    </ul>
  
    <!-- Profile -->
    <div class="mt-auto flex items-center justify-center h-12 border-t border-gray-700">
      <img class="rounded-full h-8 w-8" src="asset/user.png"/>
    </div>
  </div>
  
  
   <!-- Content (masih wadah belum ada fitur) -->
   <div class="flex-grow flex flex-col">
    <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
     <h1 class="text-xl font-light">All Tickets</h1>
     <button class="bg-blue-500 text-white px-2 py-1 text-sm font-semibold rounded">+ New Ticket</button>
    </div>
   </div>
  </div>
 </body>
</html>
