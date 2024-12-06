<body class="font-sans bg-gray-100">
    <header>
        <nav class="fixed top-0 left-0 h-full w-64 bg-gray-300 shadow-lg flex flex-col p-4 z-50">
            <a href="#" id="profile-link" class="flex flex-col items-center mb-8 p-4 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                <img src="<?= $uploads_profile . $profilePic . '?' . time() ?>" alt="Profile Image" class="w-20 h-20 rounded-full border-4 border-white mb-3 object-cover">
                <div class="text-center font-semibold text-gray-700"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            </a>
            <div id="profile-submenu" class="hidden flex-col p-4 space-y-2"> 
                <form action="/user/profile" method="post">
                    <button type="submit" class="text-gray-800 hover:text-white hover:bg-green-500 px-4 py-2 rounded flex items-center space-x-2 w-full">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </button>
                </form>
                <form action="/user/logout" method="post">
                    <button type="submit" class="text-gray-800 hover:text-white hover:bg-green-500 px-4 py-2 rounded flex items-center space-x-2 w-full">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
            <ul class="space-y-4">
                <li>
                    <a href="/adminhome" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-home mr-3"></i> Home
                    </a>
                </li>
                <li>
                    <a href="/adduser" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-home mr-3"></i> Add User
                    </a>
                </li>
                <li>
                    <a href="/managespecies" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-tree mr-3"></i> Manage Species
                    </a>
                </li>
                <li>
                    <a href="/managetrees" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-tree mr-3"></i> Manage Trees
                    </a>
                </li>
                <li>
                    <a href="/managefriends" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-tree mr-3"></i> Manage Friends
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</body>
<script>
        document.getElementById('profile-link').addEventListener('click', function(event) {
            event.preventDefault();  
            const submenu = document.getElementById('profile-submenu');
            submenu.classList.toggle('hidden'); 
        });

        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById('progress-bar').style.width = scrolled + "%"; 
        };
    </script>