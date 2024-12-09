<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container: This holds the entire sign-up form -->
        <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
            <!-- Header: The section for the page title and description -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Create an Account</h1>
                <p class="text-gray-600 text-sm">Join us to access all the features</p>
            </div>

            <!-- General Error Message: Displays any errors if the form submission fails -->
            <?php if (session()->get('error')): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                    <ul>
                        <?php foreach (session('error') as $field => $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form: The main sign-up form where the user inputs their details -->
            <form method="POST" action="/user/signup" enctype="multipart/form-data" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Profile Picture Section: Allows the user to upload and preview their profile picture -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <img id="previewImage" src="img/default_profile.png" alt="Profile Picture" class="w-24 h-24 rounded-full shadow-md">
                        <label for="profilePic" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <input type="file" name="profilePic" id="profilePic" accept="image/png, image/jpeg" class="hidden">
                </div>

                <!-- First Name Field: A required input for the user's first name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input id="first_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="first_name" value="<?= old('first_name') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['first_name'] ?? '' ?></div>
                </div>

                <!-- Last Name 1 Field: A required input for the user's first last name -->
                <div>
                    <label for="last_name1" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input id="last_name1" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="last_name1" value="<?= old('last_name1') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['last_name1'] ?? '' ?></div>
                </div>

                <!-- Last Name 2 Field: A required input for the user's second last name -->
                <div>
                    <label for="last_name2" class="block text-sm font-medium text-gray-700">Last Name 2</label>
                    <input id="last_name2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="last_name2" value="<?= old('last_name2') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['last_name2'] ?? '' ?></div>
                </div>

                <!-- Email Field: A required input for the user's email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="email" name="email" value="<?= old('email') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['email'] ?? '' ?></div>
                </div>

                <!-- Phone Field: A required input for the user's phone number -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input id="phone" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="phone" value="<?= old('phone') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['phone'] ?? '' ?></div>
                </div>

                <!-- Gender Field: A dropdown to select the user's gender -->
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

                <!-- Country, Province, and District Fields: Dropdowns to select location -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <select id="country" name="country" class="block w-full mt-1">
                        <option value="">Select Country</option>
                        <?php foreach ($country as $countries): ?>
                            <option value="<?= $countries['Id_Country'] ?>"><?= $countries['Country_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <select id="province" name="province" class="block w-full mt-1">
                        <option value="">Select Province</option>
                    </select>
                </div>

                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                    <select id="district" name="district" class="block w-full mt-1">
                        <option value="">Select District</option>
                    </select>
                </div>

                <!-- Username Field: A required input for the user's username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="username" value="<?= old('username') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['username'] ?? '' ?></div>
                </div>

                <!-- Password Field: A required input for the user's password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="password" name="password" required>
                    <div class="text-red-500 text-sm"><?= session('error')['password'] ?? '' ?></div>
                </div>

                <!-- Submit Button: The button to submit the form -->
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Sign Up
                </button>
            </form>

            <!-- Login Link: A link to the login page if the user already has an account -->
            <p class="mt-6 text-center text-gray-600 text-sm">
                Already have an account? 
                <a href="/login" class="text-blue-500 hover:underline">Log In</a>
            </p>
        </div>
    </div>
</body>

<!-- jQuery (required for AJAX) -->
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

    $(document).ready(function() {
        // Event when the country select changes, triggering AJAX to get provinces
        $('#country').on('change', function() {
            var countryId = $(this).val();  // Get the selected country ID

            // If a country is selected, make the AJAX request
            if (countryId) {
                $.ajax({
                    url: '/user/getProvinces',  // URL for getting provinces based on the country
                    type: 'POST',
                    data: { country_id: countryId },
                    dataType: 'json',  // Expect a JSON response
                    success: function(data) {
                        if (data.options) {
                            $('#province').html(data.options);  // Update the provinces dropdown
                        } else {
                            $('#province').html('<option value="">' + data.message + '</option>');  // Show message if no provinces found
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading provinces: ' + error);  // Show error if the request fails
                    }
                });
            } else {
                $('#province').html('<option value="">Select Province</option>');  // Reset the provinces dropdown
            }
        });

        // Event when the province select changes, triggering AJAX to get districts
        $('#province').on('change', function() {
            var provinceId = $(this).val();  // Get the selected province ID

            // If a province is selected, make the AJAX request
            if (provinceId) {
                $.ajax({
                    url: '/user/getDistricts',  // URL for getting districts based on the province
                    type: 'POST',
                    data: { province_id: provinceId },
                    dataType: 'json',  // Expect a JSON response
                    success: function(data) {
                        if (data.options) {
                            $('#district').html(data.options);  // Update the districts dropdown
                        } else {
                            $('#district').html('<option value="">' + data.message + '</option>');  // Show message if no districts found
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading districts: ' + error);  // Show error if the request fails
                    }
                });
            } else {
                $('#district').html('<option value="">Select District</option>');  // Reset the districts dropdown
            }
        });
    });
</script>

