<body class="bg-gray-900 text-white">
    <!-- Main section, adjusts layout for different screen sizes -->
    <section class="w-full flex flex-col md:flex-row items-center justify-around px-4">
        
        <!-- Info box section, displayed at the top with a welcome message -->
        <div class="info-box text-center md:w-1/2 px-6">
            <h2 class="text-4xl font-bold mb-6">Welcome</h2>
            <p class="text-lg mb-4">
                Log in to your account to continue.
            </p>
            <!-- Button to trigger the login form display -->
            <button class="px-6 py-2 bg-white text-gray-800 rounded-full hover:bg-gray-200 transition" onclick="showLoginForm()">
                Access Login
            </button>
        </div>

        <!-- Form container, initially hidden, will be shown when the button is clicked -->
        <div class="form-container" id="form-container">
            <div class="form-box">
                <h2>Login</h2>

                <!-- Display error message if any login errors exist -->
                <?php if (!empty($error)): ?>
                    <div class="error text-center mb-4"><?= esc($error) ?></div>
                <?php endif; ?>

                <!-- Login form: method is POST to submit credentials -->
                <form method="post" action="/user/login" class="flex flex-col">
                    <div class="inputbox">
                        <!-- Username input field -->
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>

                    <div class="inputbox">
                        <!-- Password input field -->
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <!-- Submit button for the form -->
                    <button type="submit">Login</button>
                </form>

                <!-- Registration link for users who don't have an account -->
                <div class="register">
                    <p>Don't have an account? <a href="/signup">Register here</a></p>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
        // Function to show the login form when the "Access Login" button is clicked
        function showLoginForm() {
            const formContainer = document.getElementById("form-container");
            // Add the 'show' class to display the form
            formContainer.classList.add("show");
        }
</script>


