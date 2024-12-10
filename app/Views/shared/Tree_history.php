<!-- Include Alpine.js for dynamic functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.4.2/dist/cdn.min.js" defer></script>

<div x-data="{ showModal: false, modalImage: '' }" class="container mx-auto mt-10 text-center px-4" @keydown.window.escape="showModal = false">
    <!-- Page Header -->
    <div class="bg-white shadow-lg rounded-lg p-4 max-w-4xl ml-80">
        <div class="text-center">
            <h1 class="text-6xl font-bold">Tree Updates</h1>
            <p class="text-gray-600 mt-2">Here is a list of all updates for the selected tree.</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mt-6 max-w-4xl ml-80 overflow-hidden">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden w-full">
            <thead>
                <tr class="bg-blue-200 text-gray-700">
                    <th scope="col" class="px-6 py-3 text-center">Tree Picture</th>
                    <th scope="col" class="px-6 py-3">Specie</th>
                    <th scope="col" class="px-6 py-3">Size</th>
                    <th scope="col" class="px-6 py-3">Update Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($treeUpdates)) : ?>
                <?php foreach ($treeUpdates as $update) : ?>
                    <tr class="border-b">
                        <!-- Tree Picture -->
                        <td class="text-center px-6 py-4">
                            <?php if (!empty($update['Photo_Path'])) : ?>
                                <?php 
                                    // Generar la URL de la imagen para cada actualización
                                    $image_url = base_url('uploads_tree/' . $update['Photo_Path']); 
                                ?>
                                <img 
                                    x-bind:src="'<?= $image_url . '?' . time(); ?>'"
                                    alt="Tree Picture" 
                                    class="rounded w-12 h-12 mx-auto cursor-pointer"
                                    @click="modalImage = '<?= $image_url . '?' . time(); ?>'; showModal = true">
                            <?php else : ?>
                                <span class="text-gray-400">No Image</span>
                            <?php endif; ?>
                        </td>

                        <!-- Tree Specie -->
                        <td class="px-6 py-4">
                            <?= htmlspecialchars($update['Commercial_Name']) ?> 
                            (<?= htmlspecialchars($update['Scientific_Name']) ?>)
                        </td>

                        <!-- Tree Size -->
                        <td class="px-6 py-4"><?= htmlspecialchars($update['Size']) ?></td>

                        <!-- Update Date -->
                        <td class="px-6 py-4"><?= htmlspecialchars($update['UpdateDate']) ?></td>
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
    <div 
        x-show="showModal" 
        x-cloak 
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click="showModal = false">
        <div 
            class="bg-white rounded-lg overflow-hidden shadow-xl relative" 
            @click.stop>
            <img :src="modalImage" alt="Tree Picture" class="w-full h-auto">
            <button 
                @click="showModal = false" 
                class="absolute top-2 right-2 bg-gray-800 text-white text-2xl font-bold px-2 py-1 rounded-full hover:bg-red-600">
                &times;
            </button>
        </div>
    </div>
</div>

