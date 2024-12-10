<div class="bg-gray-100 font-sans min-h-screen flex items-center justify-center">
    <!-- Card Container -->
    <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Register Update</h1>
            <p class="text-gray-600 text-sm">Update the tree information below</p>
        </div>

        <!-- General Error Message -->
        <?php if (isset($error_msg) && $error_msg): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="registerupdate" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id_tree" value="<?= htmlspecialchars($tree['Id_Tree']) ?>">

            <!-- Size -->
            <div>
                <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                <input id="size" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                       type="number" name="size" value="<?= old('size', htmlspecialchars($tree['Size'])) ?>" required>
                <div class="text-red-500 text-sm"><?= session('error')['size'] ?? '' ?></div>
            </div>

            <!-- Tree Picture (Optional) -->
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <img id="previewImage" src="<?= htmlspecialchars($tree['Photo_Path']) ?? 'img/default_tree.png' ?>" 
                         alt="Tree Picture" class="w-48 h-48 shadow-md">
                    <label for="treePic" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded cursor-pointer hover:bg-blue-600">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                <input type="file" name="treePic" id="treePic" accept="image/png, image/jpeg" class="hidden">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Update Tree
            </button>
        </form>

        <!-- Back to Manage Trees -->
        <a href="managefriends" 
            class="block text-center mt-4 text-sm text-blue-500 hover:text-blue-700">
            Manage Friends
        </a>
    </div>
</div>
