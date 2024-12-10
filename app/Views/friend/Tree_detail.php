<?php if (session()->has('message')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <?= session('message') ?>
    </div>
<?php endif; ?>
<?php if (session()->has('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <?= session('error') ?>
    </div>
<?php endif; ?>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <button onclick="goToDashboard()" class="absolute top-4 left-80 text-2xl text-blue-500 bg-transparent border-none cursor-pointer">
        <i class="fas fa-arrow-left"></i>
    </button>

    <div class="flex justify-center items-center py-12 px-8">
        <div class="bg-white max-w-6xl rounded-xl shadow-lg p-10 relative ml-60 transform transition-transform duration-500 hover:shadow-2xl">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="md:w-1/2 p-4">
                    <!-- Show tree image -->
                    <img class="w-full h-96 object-contain rounded-lg shadow-md transition-shadow duration-300 hover:shadow-xl" 
                         src="<?= $uploads_folder . $tree['Photo_Path']; ?>" alt="<?= esc($tree['Commercial_Name']); ?>">
                </div>

                <div class="md:w-1/2 p-4 space-y-6">
                    <!-- Commercial name of the tree -->
                    <div class="text-3xl font-bold text-gray-800 hover:text-blue-500 transition-colors duration-300">
                        <?= esc($tree['Commercial_Name']); ?>
                    </div>

                    <!-- Scientific name of the tree -->
                    <div class="text-xl italic text-gray-500">
                        <?= esc($tree['Scientific_Name']); ?>
                    </div>

                    <!-- Location of the tree -->
                    <div class="text-md text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1 text-red-400"></i> 
                        Location: <?= esc($tree['Location']); ?>
                    </div>

                    <!-- Price of the tree -->
                    <div class="text-4xl font-semibold text-gray-800 mt-4">
                        ₡<?= number_format($tree['Price'], 2, ',', '.'); ?>
                    </div>

                    <!-- Tree size -->
                    <div class="mt-4">
                        <label class="block text-gray-600 font-semibold">Size:</label>
                        <div class="flex space-x-2 mt-1">
                            <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full shadow hover:bg-gray-300 focus:outline-none transform transition-transform duration-300 hover:scale-105"><?= esc($tree['Size']); ?> cm</button>
                        </div>
                    </div>

                    <!-- Action Buttons (Add to Cart / Buy Now) -->
                    <div class="flex space-x-6 mt-6">
                        <form method="POST" action="<?= site_url('/addToCart'); ?>">
                            <input type="hidden" name="tree_id" value="<?= esc($tree['Id_Tree']); ?>">
                            <input type="hidden" name="price" value="<?= esc($tree['Price']); ?>">
                            <button type="submit" class="flex items-center px-6 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 focus:outline-none transition-colors duration-300" name="add_to_cart">
                                <i class="fas fa-shopping-cart mr-2"></i> Add To Cart
                            </button>
                        </form>
                        <button onclick="showPurchaseForm(<?= esc($tree['Id_Tree']); ?>)" class="flex items-center px-6 py-3 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 focus:outline-none transition-colors duration-300">
                            <i class="fas fa-money-bill-wave mr-2"></i> Buy Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for the purchase form -->
    <div id="purchaseModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto relative rounded-lg">
            <button onclick="closePurchaseForm()" class="absolute top-4 right-4 text-2xl text-red-500 bg-transparent border-none cursor-pointer">
                <i class="fas fa-times"></i>
            </button>

            <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Purchase Form</h1>

            <div id="cartItems" class="space-y-6">
    <!-- Products will be added here dynamically -->
        <div class="flex items-center space-x-4">
            <img src="<?= $uploads_folder . $tree['Photo_Path']; ?>" alt="<?= esc($tree['Commercial_Name']); ?>" class="w-20 h-20 object-cover rounded-md">
            <div>
                <h3 class="text-lg font-semibold"><?= esc($tree['Commercial_Name']); ?></h3>
                <p class="text-sm text-gray-600"><?= esc($tree['Scientific_Name']); ?></p>
                <p class="text-sm text-gray-800">Price: ₡<?= number_format($tree['Price'], 2, ',', '.'); ?></p>
            </div>
        </div>
</div>
            <!-- Purchase form -->
            <form action="<?= site_url('purchase/processPurchase'); ?>" method="POST">
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

                <button type="submit" class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 transition duration-200">Pay Now</button>
            </form>
        </div>
    </div>
</body>


<script>
    // Get the products from PHP

    function loadCartItems() {
        const cartItemsContainer = document.getElementById('cartItems'); // Make sure you have this container in the HTML
        cartItems.forEach(item => {
            const productHTML = `
                <div class="flex items-center space-x-4">
                    <img src="<?= $uploads_folder . $tree['Photo_Path'] ?>" alt="${item.Commercial_Name}" class="w-20 h-20 object-cover rounded-md">
                    <div>
                        <h3 class="text-lg font-semibold">${item.Commercial_Name}</h3>
                        <p class="text-sm text-gray-600">${item.Scientific}</p>
                        <p class="text-sm text-gray-800">Price: $${item.Price.toFixed(2)}</p>
                        <p class="text-sm text-gray-600">Quantity: ${item.Quantity}</p>
                    </div>
                </div>
            `;
            cartItemsContainer.innerHTML += productHTML; 
        });
    }

    function goToDashboard() {
        window.location.href = "/friend/dashboard"; 
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







