<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar text-white w-40 flex flex-col h-screen" style="background-color: #0056b3;">
            <div class="flex justify-between items-center py-4 px-3 border-b border-blue-900" style="background-color: #003d7a;">
                <div class="flex items-center">
                    <img src="{{ asset('asset/LogoPertamina.png') }}" class="h-7">
                </div>
                <button id="toggleSidebar" class="toggle-button flex items-center justify-center">
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
            </div>
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
                    <a href="{{ route('user.report') }}" class="menu-item py-2">
                        <i class="fas fa-chart-pie text-white text-sm"></i>
                        <span class="ml-2 text-sm font-medium">Report</span>
                    </a>
                </li>
            </ul>
            <div class="mt-auto">
                <div class="px-3 py-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-1 bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded transition duration-200 text-sm">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
                <div class="flex items-center px-3 py-3 border-t border-blue-900" style="background-color: #003d7a;">
                    <img class="rounded-full h-8 w-8 flex-shrink-0 border border-white" src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('asset/user.png') }}"/>
                    <div class="ml-2 flex flex-col w-full overflow-hidden">
                        <a href="{{ route('profile.edit') }}" class="block text-xs font-semibold leading-tight truncate text-white hover:underline">{{ Auth::user()->display_name ?? Auth::user()->name }}</a>
                        <p class="text-xs text-blue-200 leading-tight truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div id="content" class="content flex-grow flex flex-col">
            <div class="flex items-center justify-between bg-white p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <button id="openSidebar" class="toggle-button mr-3" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold">Edit Profile</h1>
                </div>
            </div>
            <div class="p-4">
                <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold mb-4">Edit Profile</h2>
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6 flex flex-col items-center">
                            <label for="profile_photo" class="cursor-pointer">
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-2 border-2 border-gray-300">
                                    @if($user->profile_photo)
                                        <img src="/{{ $user->profile_photo }}" alt="Profile Photo" class="object-cover w-full h-full">
                                    @else
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">Click to change photo</span>
                                <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Public display name</label>
                            <input type="text" name="display_name" value="{{ old('display_name', $user->display_name) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">About me</label>
                            <textarea name="about" class="mt-1 block w-full p-2 border border-gray-300 rounded-md h-20">{{ old('about', $user->about) }}</textarea>
                        </div>
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Save profile details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Sidebar toggle script (copy dari dashboard)
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

        // Preview foto profil sebelum submit
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                const img = e.target.closest('label').querySelector('img');
                if (img) {
                    img.src = URL.createObjectURL(file);
                } else {
                    const icon = e.target.closest('label').querySelector('i');
                    if (icon) icon.style.display = 'none';
                    const div = e.target.closest('label').querySelector('div');
                    const newImg = document.createElement('img');
                    newImg.className = 'object-cover w-full h-full';
                    newImg.src = URL.createObjectURL(file);
                    div.appendChild(newImg);
                }
            }
        });
    </script>
</body>
</html> 