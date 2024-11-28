<div class="content ml-64"> 
    <div class="hero-section bg-cover bg-center text-white text-center py-24" style="background-image: url('<?= base_url('img/background_friend.png'); ?>');">
        <div class="hero-content">
            <h1 class="text-4xl mb-2">Welcome to Our Tree Garden</h1>
            <p class="text-lg mb-4">Discover a variety of trees available for you. Make your choice and add life to your space!</p>
            <a href="#available-trees" class="bg-green-200 text-green-800 py-2 px-4 rounded-lg transition duration-300 hover:bg-green-300">View Available Trees</a>
        </div>
    </div>
</div>
<script>
    setTimeout(function() {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 4000);
</script>
