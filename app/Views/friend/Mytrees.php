<div class="content ml-64">
    <div class="hero-section bg-cover bg-center text-white text-center py-24" style="background-image: url('../img/background_friend_trees.jpg');">
        <div class="hero-content">
            <h1 class="text-4xl mb-2">Your Tree Collection</h1>
            <p class="text-lg mb-4">Explore your purchased trees</p>
            <a href="#available-trees" class="bg-green-200 text-green-800 py-2 px-4 rounded-lg transition duration-300 hover:bg-green-300">View Your Trees</a>
        </div>
    </div>

    <div id="available-trees" class="product-container text-center mt-12">
        <?php if (empty($trees)): ?>
            <div class="text-center mt-20">
                <p class="text-gray-500 text-lg">No trees found in your collection.</p>
                <a href="friend.php" class="mt-4 inline-block bg-green-500 text-white py-2 px-6 rounded-lg transition duration-300 hover:bg-green-600">
                    Buy Trees
                </a>
            </div>
        <?php else: ?>
            <div class="mt-12 mb-6">
                <div class="relative border-2 border-gray-300 shadow-lg rounded-lg overflow-hidden mx-4">
                    <div class="flex overflow-hidden">
                        <?php foreach ($trees as $index => $tree): 
                            $photoTree = $uploads_folder . $tree['Photo_Path'] . '?' . time(); ?>
                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?> w-full flex-shrink-0">
<a href="#" class="block bg-white rounded-lg transition-transform transform hover:scale-105" onclick="showTreeDetails(<?php echo $tree['Tree_Id']; ?>); return false;">
                                    <img src="<?php echo $photoTree; ?>" alt="Tree Image" class="w-full h-48 object-contain rounded-lg">
                                    <div class="mt-3">
                                        <h3 class="text-xl text-gray-800"><?php echo $tree['Commercial_Name']; ?></h3>
                                        <p class="text-gray-600">Location: <?php echo $tree['Location']; ?></p>
                                        <p class="text-gray-600">Purchase date: <?php echo $tree['Purchase_Date']; ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control prev absolute left-5 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-75 rounded-full text-3xl p-2 hover:bg-opacity-100" onclick="moveSlide(-1)">&#10094;</button>
                    <button class="carousel-control next absolute right-5 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-75 rounded-full text-3xl p-2 hover:bg-opacity-100" onclick="moveSlide(1)">&#10095;</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<link rel="stylesheet" href="../css/friend_trees.css">

<script>
    function showTreeDetails(idTree) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '<?php echo base_url("tree_details"); ?>' + idTree, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const detailContainer = document.createElement('div');
            detailContainer.classList.add('tree-detail-container', 'fixed', 'top-0', 'left-0', 'w-full', 'h-full', 'flex', 'justify-center', 'items-center', 'bg-white', 'bg-opacity-90', 'z-50');
            detailContainer.innerHTML = xhr.responseText + '<button onclick="closeDetail();" class="absolute top-4 right-4 bg-red-500 text-white p-2 rounded">Close</button>';
            document.body.appendChild(detailContainer);
        } else {
            console.error('Error loading tree details.');
        }
    };
    xhr.send();
}


    function closeDetail() {
        const detailContainer = document.querySelector('.tree-detail-container');
        if (detailContainer) {
            document.body.removeChild(detailContainer);
        }
        document.body.classList.remove('blur'); 
    }

    let slideIndex = 0;

    function showSlides() {
        const slides = document.getElementsByClassName("carousel-item");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
        }
        slideIndex++;
        if (slideIndex >= slides.length) { slideIndex = 0; }    
        slides[slideIndex].style.display = "block";  
    }

    function moveSlide(n) {
        const slides = document.getElementsByClassName("carousel-item");
        slides[slideIndex].style.display = "none"; 
        slideIndex += n;
        if (slideIndex < 0) slideIndex = slides.length - 1; 
        if (slideIndex >= slides.length) slideIndex = 0; 
        slides[slideIndex].style.display = "block"; 
    }

    showSlides();
    setInterval(showSlides, 5000);
</script>
