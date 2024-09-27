<!-- SIDEBAR -->
<div id="sidebar"
    class="fixed top-0 left-0 w-64 h-full bg-white text-gray-600 shadow-lg z-50 transform -translate-x-full transition-transform duration-300">
    <ul class="flex flex-col h-full">
        <div class="items-center mt-5 mb-2 text-gray-600">
        <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="inline-block w-8 h-8" id="header-icon"> <!-- Adjust width and height as needed -->
        <span class="font-extrabold text-lg ml-2">Botaniq SuperApp</span>
        </div>

        <!-- ISI: Semi-rounded buttons with white text and centered content -->
        <div class="h-full m-3 mb-5 flex flex-col justify-start">
            <button class="w-full py-3 mb-2 rounded-xl bg-blue-100 font-bold flex justify-center items-center">
                <i class="fas fa-cog mr-2"></i> Settings
            </button>
            <button class="w-full py-3 mb-2 rounded-xl bg-blue-100 font-bold flex justify-center items-center">
                <i class="fas fa-sliders-h mr-2"></i> Device Config
            </button>
            <button class="w-full py-3 mb-2 rounded-xl bg-blue-100 font-bold flex justify-center items-center">
                <i class="fas fa-robot mr-2"></i> AI Panel
            </button>
            <button class="w-full py-3 mb-2 rounded-xl bg-blue-100 font-bold flex justify-center items-center">
                <i class="fas fa-box-open mr-2"></i> Features
            </button>

            <!-- Botaniq Centre Section -->
            <div class="mt-6 mb-2 text-gray-600 italic">Botaniq Centre</div>
            <button class="w-full py-3 mb-2 rounded-xl bg-purple-100 font-bold flex justify-center items-center">
                <i class="fas fa-question-circle mr-2"></i> FAQ
            </button>
            <button class="w-full py-3 mb-2 rounded-xl bg-purple-100 font-bold flex justify-center items-center">
                <i class="fas fa-info-circle mr-2"></i> About Botaniq
            </button>
            <button class="w-full py-3 mb-2 rounded-xl bg-purple-100 font-bold flex justify-center items-center">
                <i class="fas fa-file-alt mr-2"></i> Proposals
            </button>

        </div>

        <!-- User Profile at the Bottom -->
        <a href="auth/logout" class="mt-auto py-6 mx-3 mb-5 rounded-xl bg-blue-100">
            <div class="grid grid-cols-3 items-center justify-items-center">
                <!-- Profile Picture -->
                <div class="text-right">
                    <img src="assets/user.jpg" alt="User Profile" class="rounded-full w-12 h-12">
                </div>
                <!-- User Info -->
                <div class="text-center">
                    <div class="font-bold text-xl"><?= $_SESSION['login']['username'] ?></div>
                    <div>Prototyper</div>
                </div>
                <!-- Logout Icon -->
                <li class="flex items-center justify-center text-left text-xl">
                    <i class="fas fa-sign-out-alt"></i>
                </li>
            </div>
        </a>
    </ul>
</div>

<script>
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');

    // Ensure sidebar is initially hidden
    sidebar.classList.add('-translate-x-full');

    // Event listener to toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full');
    });

    // Optional: close sidebar if clicking outside
    document.addEventListener('click', function(event) {
        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>