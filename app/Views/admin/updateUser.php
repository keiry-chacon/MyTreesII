<body class="bg-white-300 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-7xl p-16 rounded-lg shadow-lg flex flex-col justify-between min-h-screen"> <!-- Fondo blanco ocupando toda la pantalla -->
            <!-- Header -->
            <div class="mb-6 ml-60">
                <h1 class="text-4xl font-bold text-gray-800">Update User Information</h1>
                <p class="text-gray-600 text-sm">Update the user information below</p>
            </div>

            <!-- General Error Message -->
            <?php if (isset($error_msg) && $error_msg): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                    <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="/admin/updateuser" enctype="multipart/form-data" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['Id_User']) ?>">

                <!-- Profile Picture -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <!-- Mostrar la foto actual o una por defecto -->
                        <img id="previewImage" src="<?= base_url('uploads_profile/' . htmlspecialchars($user['Profile_Pic'] ?? 'default_profile.jpg')) ?>" alt="Profile Picture" class="w-24 h-24 rounded-full shadow-md">
                        <label for="profilePic" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <input type="file" name="profilePic" id="profilePic" accept="image/png, image/jpeg" class="hidden">
                </div>

                <!-- First Name -->
                <div class="ml-60">
                    <label for="first_name" class="block text-lg font-medium text-gray-700">First Name</label>
                    <input id="first_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="first_name" value="<?= old('first_name', htmlspecialchars($user['First_Name'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['first_name'] ?? '' ?></div>
                </div>

                <!-- Last Name 1 -->
                <div class="ml-60">
                    <label for="last_name1" class="block text-lg font-medium text-gray-700">Last Name</label>
                    <input id="last_name1" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="last_name1" value="<?= old('last_name1', htmlspecialchars($user['Last_Name1'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['last_name1'] ?? '' ?></div>
                </div>

                <!-- Last Name 2 -->
                <div class="ml-60">
                    <label for="last_name2" class="block text-lg font-medium text-gray-700">Last Name 2</label>
                    <input id="last_name2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="last_name2" value="<?= old('last_name2', htmlspecialchars($user['Last_Name2'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['last_name2'] ?? '' ?></div>
                </div>

                <!-- Username -->
                <div class="ml-60">
                    <label for="username" class="block text-lg font-medium text-gray-700">Username</label>
                    <input id="username" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="username" value="<?= old('username', htmlspecialchars($user['Username'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['username'] ?? '' ?></div>
                </div>

                <!-- Email -->
                <div class="ml-60">
                    <label for="email" class="block text-lg font-medium text-gray-700">Email</label>
                    <input id="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="email" name="email" value="<?= old('email', htmlspecialchars($user['Email'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['email'] ?? '' ?></div>
                </div>

                <!-- Phone -->
                <div class="ml-60">
                    <label for="phone" class="block text-lg font-medium text-gray-700">Phone</label>
                    <input id="phone" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="phone" value="<?= old('phone', htmlspecialchars($user['Phone'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['phone'] ?? '' ?></div>
                </div>

                <!-- Gender -->
                <div class="ml-60">
                    <label for="gender" class="block text-lg font-medium text-gray-700">Gender</label>
                    <select id="gender" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="gender" required>
                        <option value="M" <?= old('gender', $user['Gender']) === 'M' ? 'selected' : '' ?>>Male</option>
                        <option value="F" <?= old('gender', $user['Gender']) === 'F' ? 'selected' : '' ?>>Female</option>
                        <option value="O" <?= old('gender', $user['Gender']) === 'O' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['gender'] ?? '' ?></div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-40 bg-blue-500 ml-60 text-white py-1 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Update User
                </button>
            </form>

            <!-- Back to Manage Users -->
            <a href="manageusers" class="block text-center mt-4 text-sm text-blue-500 hover:text-blue-700">
                Manage Users
            </a>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Profile picture preview when the user selects a new image
    $('#profilePic').on('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result);  // Set the image in the preview container
        };
        reader.readAsDataURL(this.files[0]);  // Read the selected image
    });
</script>
