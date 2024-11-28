<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Add a new Species</h1>
                <p class="text-gray-600 text-sm">Insert the data </p>
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
            <form method="post" action="/admin/addspecies" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Commercial Name -->
                <div>
                    <label for="commercial_name" class="block text-sm font-medium text-gray-700">Commercial Name</label>
                    <input id="commercial_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="commercial_name" value="<?= old('commercial_name') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['commercial_name'] ?? '' ?></div>
                </div>

                <!-- Scientific Name -->
                <div>
                    <label for="scientific_name" class="block text-sm font-medium text-gray-700">Scientific Name</label>
                    <input id="scientific_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" type="text" name="scientific_name" value="<?= old('scientific_name') ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['scientific_name'] ?? '' ?></div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Add Specie
                </button>
            </form>
        </div>
    </div>
</body>
