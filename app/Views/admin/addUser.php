<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Create a New User</h1>
                <p class="text-gray-600 text-sm">Join us to access all the features</p>
            </div>

            <!-- General Error Message -->
            <?php if (session()->get('error')): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                    <ul>
                        <?php foreach (session('error') as $field => $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="/admin/adduser" enctype="multipart/form-data" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Profile Picture -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <img id="previewImage" src="img/default_profile.png" alt="Profile Picture" class="w-24 h-24 rounded-full shadow-md">
                        <label for="profilePic" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <input type="file" name="profilePic" id="profilePic" accept="image/png, image/jpeg" class="hidden">
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input id="first_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="first_name" value="<?= old('first_name') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['first_name'] ?? '' ?></div>
                </div>

                <!-- Last Name 1 -->
                <div>
                    <label for="last_name1" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input id="last_name1" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="last_name1" value="<?= old('last_name1') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['last_name1'] ?? '' ?></div>
                </div>

                <!-- Last Name 2 -->
                <div>
                    <label for="last_name2" class="block text-sm font-medium text-gray-700">Last Name 2</label>
                    <input id="last_name2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="last_name2" value="<?= old('last_name2') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['last_name2'] ?? '' ?></div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="email" name="email" value="<?= old('email') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['email'] ?? '' ?></div>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input id="phone" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="phone" value="<?= old('phone') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['phone'] ?? '' ?></div>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select id="gender" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="gender" required>
                        <option value=""    <?= old('gender') === '' ? 'selected' : '' ?>>Select Gender</option>
                        <option value="M"   <?= old('gender') === 'M' ? 'selected' : '' ?>>Male</option>
                        <option value="F"   <?= old('gender') === 'F' ? 'selected' : '' ?>>Female</option>
                        <option value="O"   <?= old('gender') === 'O' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['gender'] ?? '' ?></div>
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <select id="country" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="country" required>
                        <?php foreach ($country as $countries): ?>
                            <option value="<?= $countries['Id_Country'] ?>" <?= old('country') == $countries['Id_Country'] ? 'selected' : '' ?>><?= $countries['Country_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['country'] ?? '' ?></div>
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <select id="province" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="province" required>
                        <?php foreach ($province as $provinces): ?>
                            <option value="<?= $provinces['Id_Province'] ?>" <?= old('province') == $provinces['Id_Province'] ? 'selected' : '' ?>><?= $provinces['Province_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['province'] ?? '' ?></div>
                </div>

                <!-- District -->
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                    <select id="district" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="district" required>
                        <?php foreach ($district as $districts): ?>
                            <option value="<?= $districts['Id_District'] ?>" <?= old('district') == $districts['Id_District'] ? 'selected' : '' ?>><?= $districts['District_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['district'] ?? '' ?></div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="username" value="<?= old('username') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['username'] ?? '' ?></div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="password" name="password" required>
                    <div class="text-red-500 text-sm"><?= session('error')['password'] ?? '' ?></div>
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="role" required>
                        <option value=""    <?= old('role') === '' ? 'selected' : '' ?>>Select User Role</option>
                        <option value="1"   <?= old('role') === '1' ? 'selected' : '' ?>>Administrator</option>
                        <option value="3"   <?= old('role') === '3' ? 'selected' : '' ?>>Operator</option>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['role'] ?? '' ?></div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Add
                </button>
            </form>
        </div>
    </div>
</body>
