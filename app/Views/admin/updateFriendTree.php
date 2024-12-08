<div class="bg-gray-100 font-sans min-h-screen flex items-center justify-center">
    <!-- Card Container -->
    <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Update Tree</h1>
            <p class="text-gray-600 text-sm">Update the tree information below</p>
        </div>

        <!-- General Error Message -->
        <?php if (isset($error_msg) && $error_msg): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="/admin/updatetree" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id_tree" value="<?= htmlspecialchars($tree['Id_Tree']) ?>">

            <!-- Specie dropdown select -->
            <div>
                <label for="specie_id" class="block text-sm font-medium text-gray-700">Species</label>
                <select id="specie_id" 
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                        name="specie_id" 
                        required>
                    <?php foreach ($species as $specie): ?>
                        <option value="<?= htmlspecialchars($specie['Id_Specie']) ?>" 
                                <?= old('specie_id', $tree['Specie_Id']) == $specie['Id_Specie'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($specie['Commercial_Name']) . " (" . htmlspecialchars($specie['Scientific_Name']) . ")" ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-red-500 text-sm"><?= session('error')['specie_id'] ?? '' ?></div>
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <input id="location" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                       type="text" name="location" value="<?= old('location', htmlspecialchars($tree['Location'])) ?>" required>
                <div class="text-red-500 text-sm"><?= session('error')['location'] ?? '' ?></div>
            </div>

            <!-- Size -->
            <div>
                <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                <input id="size" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                       type="number" name="size" value="<?= old('size', htmlspecialchars($tree['Size'])) ?>" required>
                <div class="text-red-500 text-sm"><?= session('error')['size'] ?? '' ?></div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" 
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                        name="status" required>
                    <option value="1" <?= old('status', $tree['StatusT']) == "1" ? 'selected' : '' ?>>Available</option>
                    <option value="0" <?= old('status', $tree['StatusT']) == "0" ? 'selected' : '' ?>>Sold</option>
                </select>
                <div class="text-red-500 text-sm"><?= session('error')['status'] ?? '' ?></div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Update Tree
            </button>
        </form>

        <!-- Back to Manage Trees -->
        <a href="/managetrees" 
            class="block text-center mt-4 text-sm text-blue-500 hover:text-blue-700">
            Manage Trees
        </a>
    </div>
</div>
