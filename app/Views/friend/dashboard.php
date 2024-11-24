<body class="font-sans bg-gray-100">
    <header>
        <nav class="fixed top-0 left-0 h-full w-64 bg-gray-300 shadow-lg flex flex-col p-4 z-50">
            <a href="#" id="profile-link" class="flex flex-col items-center mb-8 p-4 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                <img src="" alt="Profile Image" class="w-20 h-20 rounded-full border-4 border-white mb-3 object-cover">
                <div class="text-center font-semibold text-gray-700"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            </a>
            <div id="profile-submenu" class="hidden flex-col p-4 space-y-2">
                <a href="../inc/profile.php?username=<?php echo urlencode($_SESSION['username']); ?>" class="text-gray-800 hover:text-white hover:bg-green-500 px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
                <a href="/logout" class="text-gray-800 hover:text-white hover:bg-green-500 px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
            </div>
            <ul class="space-y-4">
                <li>
                    <a href="../friend/friend.php" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-home mr-3"></i> Home
                    </a>
                </li>
                <li>
                    <a href="../friend/friends_trees.php" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-tree mr-3"></i> My Trees
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <div class="ml-64 p-8">
        <h2>Welcome to Your Friend Page</h2>
    </div>
</body>
</html>
