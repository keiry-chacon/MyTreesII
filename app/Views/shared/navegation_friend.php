<body class="font-sans bg-gray-100">
    <!-- Header section containing the sidebar navigation -->
    <header>
        <!-- Sidebar navigation bar, fixed to the left side of the screen -->
        <nav class="fixed top-0 left-0 h-full w-64 bg-gray-300 shadow-lg flex flex-col p-4 z-50">
            <!-- Profile link section -->
            <a href="#" id="profile-link" class="flex flex-col items-center mb-8 p-4 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                <img src="<?= $uploads_profile . $profilePic . '?' . time() ?>" alt="Profile Image" class="w-20 h-20 rounded-full border-4 border-white mb-3 object-cover">
                <div class="text-center font-semibold text-gray-700">
                    <?php if (isset($_SESSION['username'])): ?>
                        <?= htmlspecialchars($_SESSION['username']); ?>
                    <?php else: ?>
                        Guest
                    <?php endif; ?>
                </div>
            </a>

            <div id="profile-submenu" class="hidden flex-col p-4 space-y-2">
                <a href="/showupdateuser" class="text-gray-800 hover:text-white hover:bg-green-500 px-4 py-2 rounded flex items-center space-x-2 w-full">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
                <form action="/user/logout" method="post">
                    <button type="submit" class="text-gray-800 hover:text-white hover:bg-green-500 px-4 py-2 rounded flex items-center space-x-2 w-full">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
            <ul class="space-y-4">
                <li>
                    <a href="/friend/dashboard" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-home mr-3"></i> Home
                    </a>
                </li>
                <li>
                    <a href="/friend/mytrees" class="flex items-center px-4 py-2 text-gray-800 bg-gray-300 rounded-lg hover:bg-green-500 hover:text-white transition duration-300">
                        <i class="fas fa-tree mr-3"></i> My Trees
                    </a>
                </li>
            </ul>

            <!-- Shopping cart button at the bottom of the sidebar -->
            <button id="cartButton" class="absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-green-500 text-white p-3 rounded-full flex items-center justify-center text-xl z-50" aria-label="View Cart">
                <i class="fas fa-shopping-cart"></i>
                <span id="cartCount" class="bg-red-500 text-white rounded-full p-1 text-xs absolute -top-2 -right-2"><?= $cartCount ?></span>
            </button>
        </nav>
    </header>

    <!-- Shopping cart modal, initially hidden -->
    <div id="cartModal" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-40">
        <div class="relative w-11/12 md:w-1/2 lg:w-1/3 mx-auto mt-20 bg-white p-6 rounded-lg max-h-[80vh] overflow-hidden flex flex-col">
            <button class="absolute top-2 right-2 text-xl text-black" id="closeModal">&times;</button>
            <h2 class="text-xl font-semibold mb-4">Your Cart</h2>

            <!-- Container with overflow for cart items -->
            <div class="overflow-y-auto max-h-96 mb-6">
                <?php
                    $total = 0;
                ?>
                <?php foreach ($carts as $cart): ?>
                    <div class="mb-4 p-4 border-b flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img src="<?= $uploads_folder . $cart['photo_path'] . '?' . time(); ?>" alt="Tree Image" class="w-16 h-16 object-cover rounded-md">
                            <div>
                                <p class="text-sm font-medium"><?= htmlspecialchars($cart['scientific_name']) ?></p>
                                <p class="text-sm"><?= htmlspecialchars($cart['commercial_name']) ?></p>
                                <p class="text-sm">Quantity: <?= htmlspecialchars($cart['Quantity']) ?></p>
                                <p class="text-sm">Price: <?= htmlspecialchars($cart['Price']) ?></p>
                            </div>
                        </div>

                        <form method="POST" class="ml-4" action="<?= site_url('/friend/cartRemove'); ?>">
                            <input type="hidden" name="tree_id" value="<?= $cart['Tree_Id'] ?>" />
                            <button type="submit" name="action" value="delete" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <?php
                        $total += $cart['Price'] * $cart['Quantity'];
                    ?>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 text-center font-bold text-xl">
                <p>Total: $<?= number_format($total, 2) ?></p>
            </div>

            <!-- Form to proceed with the purchase -->
            <form method="POST" action="<?= site_url('/friend/cartBuyAll') ?>" class="relative flex flex-col gap-4">

                <div class="form-group mb-4">
                    <label for="shipping_location">Shipping Location:</label>
                    <input type="text" name="shipping_location" id="shipping_location" class="form-control w-full p-2 border rounded" required>
                </div>

                <div class="form-group mb-4">
                    <label for="payment_method">Payment Method:</label>
                    <select name="payment_method" id="payment_method" class="form-control w-full p-2 border rounded" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Cash</option>
                    </select>
                </div>

                <!-- Button stays fixed at the bottom of the modal -->
                <div class="mt-auto">
                    <button type="submit" name="action" value="buy" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 w-full">
                        Buy All
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

<script>
    document.getElementById('profile-link').addEventListener('click', function(event) {
        const submenu = document.getElementById('profile-submenu');
        submenu.classList.toggle('hidden'); 
    });

    document.getElementById('cartButton').addEventListener('click', function() {
        const cartModal = document.getElementById('cartModal');
        cartModal.classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        const cartModal = document.getElementById('cartModal');
        cartModal.classList.add('hidden');
    });

    window.onclick = function(event) {
        if (event.target === document.getElementById('cartModal')) {
            document.getElementById('cartModal').classList.add('hidden');
        }
    };
</script>

