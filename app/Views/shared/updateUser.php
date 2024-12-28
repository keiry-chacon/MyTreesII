<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex items-center justify-center py-16 px-4 ml-60">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-3xl p-12 rounded-2xl shadow-xl">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-800">Update Your Profile</h1>
                <p class="text-gray-600 text-lg mt-2">Ensure your details are up to date for a personalized experience</p>
            </div>

            <!-- General Error Message -->
            <?php if (isset($error_msg) && $error_msg): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4 text-center">
                    <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="/updateuser" enctype="multipart/form-data" class="space-y-8">
                <?= csrf_field() ?>
                <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['Id_User']) ?>">

                <!-- Profile Picture -->
                <div class="flex flex-col items-center space-y-6">
                    <div class="relative">
                        <!-- Profile Image -->
                        <img id="previewImage" src="<?= base_url('uploads_profile/' . htmlspecialchars($user['Profile_Pic'] ?? 'default_profile.jpg')) ?>" alt="Profile Picture" class="w-36 h-36 rounded-full border-4 border-gray-200 shadow-xl object-cover">
                        <label for="profilePic" class="absolute bottom-0 right-0 bg-blue-500 text-white p-3 rounded-full cursor-pointer hover:bg-blue-600">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <input type="file" name="profilePic" id="profilePic" accept="image/png, image/jpeg" class="hidden">
                </div>

                <!-- Input Fields -->
                <div class="space-y-6">
                    <!-- First Name -->
                    <div class="relative">
                        <label for="first_name" class="text-lg font-medium text-gray-700">First Name</label>
                        <input id="first_name" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" type="text" name="first_name" value="<?= old('first_name', htmlspecialchars($user['First_Name'])) ?>" required>
                        <div class="absolute top-2 right-3 text-gray-500 text-sm">🔑</div>
                        <div class="text-red-500 text-sm"><?= session('error')['first_name'] ?? '' ?></div>
                    </div>

                    <!-- Last Name 1 -->
                    <div class="relative">
                        <label for="last_name1" class="text-lg font-medium text-gray-700">Last Name 1</label>
                        <input id="last_name1" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" type="text" name="last_name1" value="<?= old('last_name1', htmlspecialchars($user['Last_Name1'])) ?>" required>
                        <div class="absolute top-2 right-3 text-gray-500 text-sm">🧑‍🤝‍🧑</div>
                        <div class="text-red-500 text-sm"><?= session('error')['last_name1'] ?? '' ?></div>
                    </div>

                    <!-- Last Name 2 -->
                    <div class="relative">
                        <label for="last_name2" class="text-lg font-medium text-gray-700">Last Name 2</label>
                        <input id="last_name2" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" type="text" name="last_name2" value="<?= old('last_name2', htmlspecialchars($user['Last_Name2'])) ?>" required>
                        <div class="absolute top-2 right-3 text-gray-500 text-sm">🧑‍🤝‍🧑</div>
                        <div class="text-red-500 text-sm"><?= session('error')['last_name2'] ?? '' ?></div>
                    </div>

                    <!-- Username -->
                    <div class="relative">
                        <label for="username" class="text-lg font-medium text-gray-700">Username</label>
                        <input id="username" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" type="text" name="username" value="<?= old('username', htmlspecialchars($user['Username'])) ?>" required>
                        <div class="absolute top-2 right-3 text-gray-500 text-sm">👤</div>
                        <div class="text-red-500 text-sm"><?= session('error')['username'] ?? '' ?></div>
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <label for="email" class="text-lg font-medium text-gray-700">Email</label>
                        <input id="email" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" type="email" name="email" value="<?= old('email', htmlspecialchars($user['Email'])) ?>" required>
                        <div class="absolute top-2 right-3 text-gray-500 text-sm">📧</div>
                        <div class="text-red-500 text-sm"><?= session('error')['email'] ?? '' ?></div>
                    </div>

                    <!-- Phone -->
                    <div class="relative">
                        <label for="phone" class="text-lg font-medium text-gray-700">Phone</label>
                        <input id="phone" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" type="text" name="phone" value="<?= old('phone', htmlspecialchars($user['Phone'])) ?>" required>
                        <div class="absolute top-2 right-3 text-gray-500 text-sm">📱</div>
                        <div class="text-red-500 text-sm"><?= session('error')['phone'] ?? '' ?></div>
                    </div>

                    <!-- Gender -->
                    <div class="relative">
                        <label for="gender" class="text-lg font-medium text-gray-700">Gender</label>
                        <select id="gender" class="block w-full mt-2 border-gray-300 rounded-lg px-4 py-3" name="gender" required>
                            <option value="M" <?= old('gender', $user['Gender']) === 'M' ? 'selected' : '' ?>>Male</option>
                            <option value="F" <?= old('gender', $user['Gender']) === 'F' ? 'selected' : '' ?>>Female</option>
                            <option value="O" <?= old('gender', $user['Gender']) === 'O' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <div class="text-red-500 text-sm"><?= session('error')['gender'] ?? '' ?></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 text-center space-x-4">
                    <button type="submit" class="w-48 bg-blue-600 text-white py-3 px-8 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                        Update Profile
                    </button>
                    <button type="reset" class="w-48 bg-gray-400 text-white py-3 px-8 rounded-lg shadow-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                        Reset
                    </button>
                </div>
            </form>
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






