<!-- LEFT SIDEBAR - Main Menu -->
<div id="sidebar" class="fixed top-0 left-0 w-72 h-full bg-gray-50 text-gray-600 shadow-lg z-40 transform -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full">
        <!-- Header with Logo -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-3xl m-4 p-4">
            <div class="flex items-center">
                <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-8 h-8" id="header-icon">
                <span class="font-extrabold text-lg ml-2">Botaniq SuperApp</span>
            </div>
            <div class="mt-2 text-sm opacity-90">Welcome back Gardener!</div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 gap-2 px-4 mb-4">
            <div class="bg-green-100 rounded-2xl p-3 text-center">
                <i class="fas fa-leaf text-green-500 text-xl mb-1"></i>
                <div class="text-xs font-bold text-gray-700">Plants Active</div>
                <div class="text-lg font-bold text-green-600">12</div>
            </div>
            <div class="bg-blue-100 rounded-2xl p-3 text-center">
                <i class="fas fa-tint text-blue-500 text-xl mb-1"></i>
                <div class="text-xs font-bold text-gray-700">Water Level</div>
                <div class="text-lg font-bold text-blue-600">85%</div>
            </div>
        </div>

        <!-- Main Menu Section -->
        <div class="flex-1 px-4 pb-4">
            <div class="bg-white rounded-2xl shadow-sm p-3 mb-4">
                <div class="mb-6 space-y-2">
                    <button class="w-full py-3 px-4 rounded-xl bg-blue-100 hover:bg-blue-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-cog mr-3 group-hover:rotate-90 transition-transform"></i> 
                        <span class="flex-1 text-left">Settings</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                    <button class="w-full py-3 px-4 rounded-xl bg-blue-100 hover:bg-blue-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-sliders-h mr-3"></i>
                        <span class="flex-1 text-left">Device Config</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                    <button class="w-full py-3 px-4 rounded-xl bg-blue-100 hover:bg-blue-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-robot mr-3"></i>
                        <span class="flex-1 text-left">AI Panel</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                    <button class="w-full py-3 px-4 rounded-xl bg-blue-100 hover:bg-blue-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-box-open mr-3"></i>
                        <span class="flex-1 text-left">Features</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-3">
                <div class="text-gray-600 italic mb-2 px-2 flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-2"></i>
                    Botaniq Centre
                </div>
                <div class="space-y-2">
                    <button class="w-full py-3 px-4 rounded-xl bg-purple-100 hover:bg-purple-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-question-circle mr-3"></i>
                        <span class="flex-1 text-left">FAQ</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                    <button class="w-full py-3 px-4 rounded-xl bg-purple-100 hover:bg-purple-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-info-circle mr-3"></i>
                        <span class="flex-1 text-left">About Botaniq</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                    <button class="w-full py-3 px-4 rounded-xl bg-purple-100 hover:bg-purple-200 font-bold flex items-center text-gray-700 group transition-all">
                        <i class="fas fa-file-alt mr-3"></i>
                        <span class="flex-1 text-left">Proposals</span>
                        <i class="fas fa-chevron-right opacity-50"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- User Profile Section -->
        <!-- User Profile Section -->
        <div class="mt-auto px-4 mb-6">
            <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-3xl p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="assets/profile.webp" alt="User Profile" class="rounded-full w-10 h-10 border-2 border-white shadow-sm">
                        <div>
                            <div class="font-bold text-gray-700"><?= $_SESSION['login']['username'] ?></div>
                            <div class="text-xs text-gray-600">Prototyper</div>
                        </div>
                    </div>
                    <a href="auth/logout" class="text-gray-600 hover:text-gray-800 bg-white/80 hover:bg-white p-2 rounded-xl shadow-sm transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RIGHT SIDEBAR - Notifications -->
<div id="sidebar-notif-panel" class="fixed top-0 right-0 w-72 h-full bg-gray-50 text-gray-600 shadow-lg z-40 transform translate-x-full transition-transform duration-300 ease-in-out">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-3xl m-4 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg">Notifications</h2>
                <div class="text-sm opacity-90">Stay updated with your garden</div>
            </div>
            <button id="close-notif" class="bg-white/20 rounded-xl p-2 hover:bg-white/30">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="flex mt-3 gap-2">
            <button class="px-4 py-1.5 bg-white text-gray-700 rounded-xl text-sm font-bold">All</button>
            <button class="px-4 py-1.5 bg-white/20 text-white rounded-xl text-sm">Unread</button>
            <button class="px-4 py-1.5 bg-white/20 text-white rounded-xl text-sm">Alerts</button>
        </div>
    </div>

    <!-- Notifications Counter -->
    <div class="grid grid-cols-3 gap-2 px-4 mb-4">
        <div class="bg-blue-100 rounded-2xl p-3 text-center">
            <div class="text-xs font-bold text-gray-700">New</div>
            <div class="text-lg font-bold text-blue-600">3</div>
        </div>
        <div class="bg-yellow-100 rounded-2xl p-3 text-center">
            <div class="text-xs font-bold text-gray-700">Alerts</div>
            <div class="text-lg font-bold text-yellow-600">1</div>
        </div>
        <div class="bg-green-100 rounded-2xl p-3 text-center">
            <div class="text-xs font-bold text-gray-700">Done</div>
            <div class="text-lg font-bold text-green-600">8</div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="px-4 pb-20 space-y-4">
        <!-- Today Section -->
        <div class="bg-white rounded-2xl shadow-sm p-3">
            <h3 class="text-sm font-semibold text-gray-500 mb-2 px-2 flex items-center">
                <i class="fas fa-calendar-day mr-2"></i> TODAY
            </h3>
            
            <!-- Notification Items -->
            <div class="space-y-2">
                <div class="bg-blue-50 rounded-xl p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-500 flex items-center justify-center text-white">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-700">Water Level Alert</p>
                            <p class="text-sm text-gray-600">Tank 1 needs refilling soon</p>
                            <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                        </div>
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-xl p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-green-500 flex items-center justify-center text-white">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-700">Watering Complete</p>
                            <p class="text-sm text-gray-600">Zone 2 watering cycle finished</p>
                            <p class="text-xs text-gray-500 mt-1">5 hours ago</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-xl p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-yellow-500 flex items-center justify-center text-white">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-700">System Alert</p>
                            <p class="text-sm text-gray-600">Check pH levels in Zone 1</p>
                            <p class="text-xs text-gray-500 mt-1">8 hours ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Bar -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-white border-t">
        <div class="grid grid-cols-2 gap-2">
            <button class="py-2 px-4 bg-blue-100 text-blue-600 rounded-xl text-sm font-bold hover:bg-blue-200">
                Mark all read
            </button>
            <button class="py-2 px-4 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200">
                Clear all
            </button>
        </div>
    </div>
</div>
<!-- Overlay untuk background dimming saat sidebar terbuka -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden transition-opacity duration-300"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const notifToggle = document.getElementById('sidebar-notif');
    const sidebar = document.getElementById('sidebar');
    const notifPanel = document.getElementById('sidebar-notif-panel');
    const closeNotifBtn = document.getElementById('close-notif');
    const overlay = document.getElementById('sidebar-overlay');

    // Initialize state
    let isLeftSidebarOpen = false;
    let isRightSidebarOpen = false;

    // Function to toggle overlay
    function toggleOverlay(show) {
        if (show) {
            overlay.classList.remove('hidden');
        } else {
            overlay.classList.add('hidden');
        }
    }

    // Left Sidebar Toggle
    sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        isLeftSidebarOpen = !isLeftSidebarOpen;
        sidebar.classList.toggle('-translate-x-full');
        toggleOverlay(isLeftSidebarOpen);
    });

    // Right Sidebar Toggle
    notifToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        isRightSidebarOpen = !isRightSidebarOpen;
        notifPanel.classList.toggle('translate-x-full');
        toggleOverlay(isRightSidebarOpen);
    });

    // Close button for notification panel
    closeNotifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifPanel.classList.add('translate-x-full');
        isRightSidebarOpen = false;
        toggleOverlay(false);
    });

    // Overlay click handler
    overlay.addEventListener('click', function() {
        if (isLeftSidebarOpen) {
            sidebar.classList.add('-translate-x-full');
            isLeftSidebarOpen = false;
        }
        if (isRightSidebarOpen) {
            notifPanel.classList.add('translate-x-full');
            isRightSidebarOpen = false;
        }
        toggleOverlay(false);
    });

    // Document click handler for closing sidebars when clicking outside
    document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && isLeftSidebarOpen) {
            sidebar.classList.add('-translate-x-full');
            isLeftSidebarOpen = false;
            toggleOverlay(false);
        }
        
        if (!notifPanel.contains(e.target) && !notifToggle.contains(e.target) && isRightSidebarOpen) {
            notifPanel.classList.add('translate-x-full');
            isRightSidebarOpen = false;
            toggleOverlay(false);
        }
    });
});
</script>