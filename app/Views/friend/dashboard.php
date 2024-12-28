<div class="content ml-64">
    <!-- Hero Section -->
    <div class="hero-section bg-cover bg-center text-white text-center py-24 relative overflow-hidden" style="background-image: url('<?= base_url('img/background_friend.png'); ?>');">
        <!-- Degradado más sutil para mejorar la visibilidad de la imagen -->
        <div class="absolute inset-0 bg-gradient-to-b from-black to-transparent opacity-60"></div>
        <div class="relative z-10 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg tracking-wide">
                Welcome to Our Tree Garden
            </h1>
            <p class="text-lg md:text-xl mb-6 drop-shadow-md">
                Discover a variety of trees available for you. Make your choice and add life to your space!
            </p>
            <a href="#available-trees" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 py-3 px-6 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105 animate-bounce-once">
                View Available Trees
            </a>
        </div>
    </div>
    <!-- Search Bar -->
    <div class="search-bar bg-gray-100 py-6 px-4 text-center sticky top-0 z-20 shadow-md">
        <input 
            type="text" 
            id="search-trees" 
            placeholder="Search for a tree..." 
            class="w-full md:w-1/2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-400 focus:outline-none transition-all">
    </div>

    <!-- Product Section -->
    <div id="available-trees" class="product-container text-center mt-12 px-4 lg:px-16">
        <h2 class="text-3xl font-semibold mb-8 text-green-700 animate-fade-in">Available Trees</h2>
        
        <!-- Loading Spinner -->
        <div id="loading-spinner" class="flex justify-center items-center mb-8" style="display: none;">
            <div class="animate-spin rounded-full border-t-4 border-green-500 w-12 h-12"></div>
        </div>

        <div class="tree-cards grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="tree-list">
            <?php foreach ($trees as $tree): ?>
                <a href="<?= site_url('/friend/tree_detail/' . $tree['Id_Tree']); ?>" 
                   class="card bg-white rounded-lg border border-gray-300 shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105 p-4 animate-slide-up">
                    <div class="image-wrapper w-full h-64 bg-gray-100 rounded-lg overflow-hidden mb-4">
                        <img src="<?= $uploads_folder . $tree['Photo_Path'] . '?' . time(); ?>" 
                             alt="Tree Image" 
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" 
                             onload="imageLoaded()">
                    </div>
                    <div class="card-content">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= esc($tree['Commercial_Name']); ?></h3>
                        <p class="text-gray-600"><i class="fas fa-map-marker-alt text-red-500"></i> Location: <?= esc($tree['Location']); ?></p>
                        <p class="text-gray-600"><i class="fas fa-tag text-green-500"></i> Price: ₡<?= number_format($tree['Price'], 0, ',', '.'); ?></p>
                        <button class="bg-green-500 text-white py-2 px-4 mt-4 rounded-lg shadow-md hover:bg-green-600 transition-transform duration-300 transform hover:scale-105">
                            View Details
                        </button>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Tailwind Animations -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes bounce-once {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    .animate-fade-in {
        animation: fade-in 1.2s ease-in-out;
    }
    .animate-slide-up {
        animation: slide-up 0.8s ease-in-out;
    }
    .animate-bounce-once {
        animation: bounce-once 1.5s ease infinite;
    }
</style>

<script>
    // Hide Success Message
    setTimeout(function() {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 4000);

    // Show spinner while images are loading
    function imageLoaded() {
        const spinner = document.getElementById('loading-spinner');
        const images = document.querySelectorAll('img');
        let imagesLoaded = 0;

        images.forEach((img) => {
            img.onload = () => {
                imagesLoaded++;
                if (imagesLoaded === images.length) {
                    spinner.style.display = 'none';
                }
            };
        });
    }

    // Live Search
    const searchInput = document.getElementById('search-trees');
    const treeList = document.getElementById('tree-list');
    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const cards = treeList.querySelectorAll('.card');
        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            if (name.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>


