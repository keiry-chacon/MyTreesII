<body class="bg-white-300 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <!-- Card Container -->
        <div class="bg-white w-full max-w-7xl p-16 rounded-lg shadow-lg flex flex-col justify-between min-h-screen"> <!-- Fondo blanco ocupando toda la pantalla -->
            <!-- Header -->
            <div class="mb-10 ml-60 text-center sm:text-left">
                <h1 class="text-4xl font-bold text-gray-800 leading-tight mb-2">
                    Update Tree Information
                </h1>
                <p class="text-lg text-gray-600">
                    Please update the details of the tree below to keep the information up to date.
                </p>
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
                <div class="ml-60">
                    <label for="size" class="block text-lg font-medium text-gray-700">Size</label>
                    <div class="relative">
                        <!-- Input with enhanced design -->
                        <input id="size" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-lg h-12 pl-4 pr-12 bg-white border-2" 
                            type="number" name="size" value="<?= old('size', htmlspecialchars($tree['Size'])) ?>" required>
                        <span class="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500 text-lg">cm</span>
                    </div>
                    <div class="text-red-500 text-sm"><?= session('error')['size'] ?? '' ?></div>
                </div>

                <!-- Tree Picture (Optional) -->
                <div class="relative right-60 flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="border-4 border-green-700 rounded-xl shadow-lg p-1  bg-cover bg-center bg-opacity-30">
                            <img id="previewImage" src="<?= htmlspecialchars($tree['Photo_Path']) ?? 'img/default_tree.png' ?>" 
                                alt="Tree Picture" class="w-48 h-48 object-cover rounded-lg shadow-md">
                        </div>
                        <label for="treePic" class="absolute bottom-0 right-0 bg-green-600 text-white p-2 rounded cursor-pointer hover:bg-green-700">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <input type="file" name="treePic" id="treePic" accept="image/png, image/jpeg" class="hidden">
                </div>

                <!-- Submit Button -->
                <div class="ml-60">
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                        Register Update Tree
                    </button>
                </div>
            </form>

        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Profile picture preview when the user selects a new image
    $('#treePic').on('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result);  // Set the image in the preview container
        };
        reader.readAsDataURL(this.files[0]);  // Read the selected image
    });
</script>
