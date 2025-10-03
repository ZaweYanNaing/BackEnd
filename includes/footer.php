`        </main>
        
        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-sm"></i>
                            </div>
                            <span class="text-xl font-bold">FoodFusion</span>
                        </div>
                        <p class="text-gray-300 mb-4 max-w-md">
                            A culinary platform dedicated to promoting home cooking and culinary 
                            creativity among food enthusiasts. Share recipes, learn techniques, 
                            and connect with fellow cooking enthusiasts.
                        </p>
                        <div class="flex space-x-4">
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="https://www.twitter.com/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-youtube text-xl"></i>
                            </a>
                            <a href="https://www.pinterest.com/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-pinterest text-xl"></i>
                            </a>
                            <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-tiktok text-xl"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="index.php" class="text-gray-300 hover:text-white">Home</a></li>
                            <li><a href="index.php?page=recipes" class="text-gray-300 hover:text-white">Recipes</a></li>
                            <li><a href="index.php?page=culinary" class="text-gray-300 hover:text-white">Culinary</a></li>
                            <li><a href="index.php?page=search" class="text-gray-300 hover:text-white">Search</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="index.php?page=contact" class="text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="index.php?page=contact" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="index.php?page=privacy" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="index.php?page=terms" class="text-gray-300 hover:text-white transition-colors">Terms of Service</a></li>
                            <li><a href="index.php?page=cookies" class="text-gray-300 hover:text-white transition-colors">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-400">&copy; 2024 FoodFusion. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Sidebar functionality
        let sidebarCollapsed = false;
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const mobileOverlay = document.getElementById('mobile-overlay');
            
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('open');
                mobileOverlay.classList.toggle('open');
            } else {
                // Desktop behavior
                sidebarCollapsed = !sidebarCollapsed;
                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('sidebar-collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                }
            }
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');
            sidebar.classList.remove('open');
            mobileOverlay.classList.remove('open');
        }
        
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('open');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('open');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const mobileOverlay = document.getElementById('mobile-overlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
                mobileOverlay.classList.remove('open');
            } else {
                if (!sidebarCollapsed) {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                }
            }
        });
        
        // Copy to clipboard function
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Link copied to clipboard!', 'success');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Link copied to clipboard!', 'success');
            }
        }
        
        // Toast notification
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 max-w-sm`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        // Show toast from PHP session
        <?php if (isset($_SESSION['toast_message'])): ?>
            showToast('<?php echo addslashes($_SESSION['toast_message']); ?>', '<?php echo $_SESSION['toast_type'] ?? 'info'; ?>');
            <?php unset($_SESSION['toast_message'], $_SESSION['toast_type']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
