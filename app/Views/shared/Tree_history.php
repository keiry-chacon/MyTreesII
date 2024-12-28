<!-- Back Arrow -->
<div class="mt-4 flex justify-start">
    <a href="javascript:history.back()" class="text-blue-600 text-2xl hover:text-blue-800 transition-all duration-300 flex items-center ml-80">
        <i class="fas fa-arrow-left mr-2"></i>
    </a>
</div>

<!-- Include Alpine.js for dynamic functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.4.2/dist/cdn.min.js" defer></script>

<div x-data="{ showModal: false, modalImage: '' }" class="container mx-auto mt-10 text-center px-4" @keydown.window.escape="showModal = false">
    <!-- Page Header -->
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl ml-80">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-gray-800">Tree Updates</h1>
            <p class="text-gray-600 mt-2 text-xl">Here is a list of all updates for the selected tree.</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mt-6 max-w-4xl ml-80 overflow-hidden">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden w-full">
            <thead>
                <tr class="bg-blue-100 text-gray-800">
                    <th scope="col" class="px-6 py-3 text-center">Tree Picture</th>
                    <th scope="col" class="px-6 py-3">Specie</th>
                    <th scope="col" class="px-6 py-3">Size</th>
                    <th scope="col" class="px-6 py-3">Update Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($treeUpdates)) : ?>
                <?php foreach ($treeUpdates as $update) : ?>
                    <tr class="border-b hover:bg-gray-50 transition-all duration-300">
                    <!-- Tree Picture -->
                        <td class="text-center px-6 py-4">
                            <?php if (!empty($update['Photo_Path'])) : ?>
                                <?php $image_url = base_url('uploads_tree/' . $update['Photo_Path']); ?>
                                <img 
                                    src="<?= $image_url ?>" 
                                    alt="Tree Picture" 
                                    class="rounded-full w-16 h-16 mx-auto cursor-pointer transform hover:scale-105 transition-all duration-200"
                                    @click="modalImage = '<?= $image_url ?>'; showModal = true">
                            <?php else : ?>
                                <span class="text-gray-400">No Image</span>
                            <?php endif; ?>
                        </td>

                        <!-- Tree Specie -->
                        <td class="px-6 py-4 text-lg">
                            <?= htmlspecialchars($update['Commercial_Name']) ?> 
                            <span class="text-gray-500 text-sm">(<?= htmlspecialchars($update['Scientific_Name']) ?>)</span>
                        </td>

                        <!-- Tree Size -->
                        <td class="px-6 py-4 text-lg"><?= htmlspecialchars($update['Size']) ?></td>

                        <!-- Update Date -->
                        <td class="px-6 py-4 text-lg"><?= htmlspecialchars($update['UpdateDate']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4" class="text-center text-gray-500 py-4">No Updates found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

     <!-- Modal -->
     <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" @click="showModal = false">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl relative max-w-lg" @click.stop>
            <img :src="modalImage" alt="Tree Picture" class="w-full h-auto rounded-lg">
            <button @click="showModal = false" class="absolute top-2 right-2 bg-gray-800 text-white text-2xl font-bold px-2 py-1 rounded-full hover:bg-red-600 focus:outline-none transition-all duration-300">
                &times;
            </button>
        </div>
    </div>
</div>



