<body class="bg-white-300 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-7xl p-16 rounded-lg shadow-lg flex flex-col justify-between min-h-screen"> <!-- Fondo blanco ocupando toda la pantalla -->
            <!-- Header -->
            <div class="mb-6 ml-60">
                <h1 class="text-4xl font-bold text-gray-800">Update Tree</h1>
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
                <div class="ml-60">
                    <label for="specie_id" class="block text-lg font-medium text-gray-700">Species</label>
                    <select id="specie_id" 
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
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
                <div class="ml-60">
                    <label for="location" class="block text-lg font-medium text-gray-700">Location</label>
                    <input id="location" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
                        type="text" name="location" value="<?= old('location', htmlspecialchars($tree['Location'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['location'] ?? '' ?></div>
                </div>

                <!-- Size -->
                <div class="ml-60">
                    <label for="size" class="block text-lg font-medium text-gray-700">Size</label>
                    <input id="size" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
                        type="number" name="size" value="<?= old('size', htmlspecialchars($tree['Size'])) ?>" required>
                    <div class="text-red-500 text-sm"><?= session('error')['size'] ?? '' ?></div>
                </div>

                <!-- Status -->
                <div class="ml-60">
                    <label for="status" class="block text-lg font-medium text-gray-700">Status</label>
                    <select id="status" 
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
                            name="status" required>
                        <option value="1" <?= old('status', $tree['StatusT']) == "1" ? 'selected' : '' ?>>Available</option>
                        <option value="0" <?= old('status', $tree['StatusT']) == "0" ? 'selected' : '' ?>>Sold</option>
                    </select>
                    <div class="text-red-500 text-sm"><?= session('error')['status'] ?? '' ?></div>
                </div>

                <!-- Price -->
                <div class="ml-60">
                    <label for="price" class="block text-lg font-medium text-gray-700">Price</label>
                    <input id="price" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg h-12 py-3" 
                        type="number" name="price" value="<?= old('price', htmlspecialchars($tree['Price'])) ?>" step="0.01" min="0" max="99999999.99" required>
                    <div class="text-red-500 text-sm"><?= session('error')['price'] ?? '' ?></div>
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
                Manage Trees
            </a>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Profile picture preview when the user selects a new image
    $('#treePic').on('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result);  // Establecer la imagen en el contenedor de vista previa
        };
        if (this.files && this.files[0]) {
            reader.readAsDataURL(this.files[0]);  // Leer la imagen seleccionada
        }
    });

</script>
