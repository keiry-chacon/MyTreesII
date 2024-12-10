<div class="bg-gray-100 font-sans min-h-screen flex items-center justify-center">
    <!-- Card Container -->
    <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Update Species</h1>
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
            <div>
                <label for="commercial_name" class="block text-sm font-medium text-gray-700">Commercial Name</label>
                <input id="commercial_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                    type="text" name="commercial_name" value="<?= old('commercial_name', htmlspecialchars($specie['Commercial_Name'])) ?>" required>
            </div>

            <!-- Scientific Name -->
            <div>
                <label for="scientific_name" class="block text-sm font-medium text-gray-700">Scientific Name</label>
                <input id="scientific_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                    type="text" name="scientific_name" value="<?= old('scientific_name', htmlspecialchars($specie['Scientific_Name'])) ?>" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Update Specie
            </button>
        </form>

        <!-- Back to Manage Species -->
        <a href="managespecies" 
            class="block text-center mt-4 text-sm text-blue-500 hover:text-blue-700">
            Manage Species
        </a>
    </div>
</div>
