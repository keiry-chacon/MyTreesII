<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<button onclick="goToDashboard()" class="absolute top-4 left-80 text-2xl text-blue-500 bg-transparent border-none cursor-pointer">
    <i class="fas fa-arrow-left"></i>
</button>

<div class="flex justify-center items-center py-12 px-8">
    <div class="bg-white max-w-6xl rounded-xl shadow-lg p-10 relative ml-60 transform transition-transform duration-500 hover:shadow-2xl">
        
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="md:w-1/2 p-4">
                <img class="w-full h-96 object-contain rounded-lg shadow-md transition-shadow duration-300 hover:shadow-xl" src="<?= $uploads_folder . $tree['Photo_Path']; ?>" alt="<?= esc($tree['Commercial_Name']); ?>">
            </div>

            <div class="md:w-1/2 p-4 space-y-6">
                
                <div class="text-3xl font-bold text-gray-800 hover:text-blue-500 transition-colors duration-300">
                    <?= esc($tree['Commercial_Name']); ?>
                </div>

                <div class="text-xl italic text-gray-500">
                    <?= esc($tree['Scientific_Name']); ?>
                </div>

                <div class="text-md text-gray-600">
                    <i class="fas fa-map-marker-alt mr-1 text-red-400"></i> 
                    Location: <?= esc($tree['Location']); ?>
                </div>

                <div class="text-4xl font-semibold text-gray-800 mt-4">
                    ₡<?= number_format($tree['Price'], 2, ',', '.'); ?>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-600 font-semibold">Size:</label>
                    <div class="flex space-x-2 mt-1">
                        <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full shadow hover:bg-gray-300 focus:outline-none transform transition-transform duration-300 hover:scale-105"><?= esc($tree['Size']); ?> cm</button>
                    </div>
                </div>

                <!-- Tree History Button -->
                <div class="mt-6">
                        <a href="/treehistory?id_tree=<?= $tree['Id_Tree']; ?>" 
                           class="inline-block bg-blue-500 text-white px-6 py-2 rounded-full shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none transform transition-transform duration-300 hover:scale-105">
                            Tree History
                        </a>
                    </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Modal for Purchase Form -->
<div id="purchaseModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-center">
    <div class="bg-white p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto relative rounded-lg">
        <!-- Botón de cierre -->
        <button onclick="closePurchaseForm()" class="absolute top-4 right-4 text-2xl text-red-500 bg-transparent border-none cursor-pointer">
            <i class="fas fa-times"></i>
        </button>

        <!-- Título -->
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Purchase Form</h1>

        <!-- Contenido del formulario -->
        <div>
          
            <!-- Formulario de compra -->
                <input type="hidden" name="tree_id" id="tree_id" value="<?= $tree['Id_Tree']; ?>">

                <div class="mb-4">
                    <label for="shipping_location" class="block text-gray-700 font-semibold mb-2">Shipping Location:</label>
                    <input type="text" id="shipping_location" name="shipping_location" required placeholder="Enter your shipping address" class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-400">
                </div>

                <div class="mb-6">
                    <label for="payment_method" class="block text-gray-700 font-semibold mb-2">Payment Method:</label>
                    <select id="payment_method" name="payment_method" required class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-400">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                    </select>
                </div>

                
        </div>
    </div>
</div>
</body>

<script>
 
    function goToDashboard() {
        window.location.href = "/mytrees"; // Reemplaza con la URL de tu dashboard
    }
    function showPurchaseForm(treeId) {
        document.getElementById('tree_id').value = treeId;
        document.getElementById('purchaseModal').classList.remove('hidden');
        document.getElementById('purchaseModal').classList.add('flex');
    }

    function closePurchaseForm() {
        document.getElementById('purchaseModal').classList.add('hidden');
        document.getElementById('purchaseModal').classList.remove('flex');
    }
</script>


