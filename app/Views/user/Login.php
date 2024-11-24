<body class="bg-gray-900 text-white">
    <section class="w-full flex flex-col md:flex-row items-center justify-around px-4">
        <div class="info-box text-center md:w-1/2 px-6">
            <h2 class="text-4xl font-bold mb-6">Welcome</h2>
            <p class="text-lg mb-4">
                Log in to your account to continue.
            </p>
            <button class="px-6 py-2 bg-white text-gray-800 rounded-full hover:bg-gray-200 transition" onclick="showLoginForm()">
                Access Login
            </button>
        </div>

        <div class="form-container" id="form-container">
            <div class="form-box">
                <h2>Login</h2>

                <!-- Display error message if it exists -->
                <?php if (!empty($error)): ?>
                    <div class="error text-center mb-4"><?= esc($error) ?></div>
                <?php endif; ?>

                <form method="post" action="/user/login" class="flex flex-col">
                    <div class="inputbox">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>

                    <div class="inputbox">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <button type="submit">Login</button>
                </form>

                <div class="register">
                    <p>Don't have an account? <a href="/signup">Register here</a></p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Function to show the login form when the button is clicked
        function showLoginForm() {
            const formContainer = document.getElementById("form-container");
            formContainer.classList.add("show");
        }
    </script>
</body>


