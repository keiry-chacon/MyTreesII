<div class="flex justify-center mt-20 px-4">
    <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg border border-gray-200 p-6 transform transition hover:scale-105">
        <div class="flex flex-col md:flex-row">
            <div class="flex-shrink-0 md:w-1/2 mb-4 md:mb-0">
                <img src="<?php echo base_url('uploads_tree/' . $tree['Photo_Path']); ?>" alt="<?php echo $tree['Commercial_Name']; ?>" class="w-full h-auto max-h-72 object-contain rounded-lg shadow-md">
            </div>
            <div class="md:ml-6 w-full">
                <h1 class="text-2xl font-semibold text-gray-800"><?php echo $tree['Commercial_Name']; ?></h1>
                <h2 class="text-xl text-gray-600"><?php echo $tree['Scientific_Name']; ?></h2>
                <p class="text-xl text-green-600 font-bold my-4">₡<?php echo number_format($tree['Price'], 0, ',', '.'); ?></p>
                <div class="text-gray-700 space-y-2">
                    <p><strong>Location:</strong> <?php echo $tree['Location']; ?></p>
                    <p><strong>Size:</strong> <?php echo $tree['Size'] . ' cm'; ?></p>
                    <p><strong>Purchase Date:</strong> <?php echo date("F j, Y", strtotime($tree['Purchase_Date'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
