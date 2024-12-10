<body class="bg-white-300 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-7xl p-16 rounded-lg shadow-lg flex flex-col justify-between min-h-screen"> <!-- Fondo blanco ocupando toda la pantalla -->
            <!-- Header -->
            <div class="mb-6 ml-60">
                <h1 class="text-4xl font-bold text-gray-800">Update Species</h1>
                <p class="text-gray-600 text-sm">Update the species information below</p>
            </div>

            <!-- General Error Message -->
            <?php if (isset($error_msg) && $error_msg): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                    <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="/admin/updatespecies" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="id_specie" value="<?= htmlspecialchars($specie['Id_Specie']) ?>">

                <!-- Commercial Name -->
                <div class="ml-60">
                    <label for="commercial_name" class="block text-lg font-medium text-gray-700">Commercial Name</label>
                    <input id="commercial_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
                        type="text" name="commercial_name" value="<?= old('commercial_name', htmlspecialchars($specie['Commercial_Name'])) ?>" required>
                </div>

                <!-- Scientific Name -->
                <div class="ml-60">
                    <label for="scientific_name" class="block text-lg font-medium text-gray-700">Scientific Name</label>
                    <input id="scientific_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
                        type="text" name="scientific_name" value="<?= old('scientific_name', htmlspecialchars($specie['Scientific_Name'])) ?>" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-40 bg-blue-500 ml-60 text-white py-1 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Update Specie
                </button>
            </form>

            <!-- Back to Manage Species -->
            <a href="managespecies" 
                class="w-40 bg-blue-500 ml-60 text-white py-1 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Manage Species
            </a>
        </div>
    </div>
</div>
