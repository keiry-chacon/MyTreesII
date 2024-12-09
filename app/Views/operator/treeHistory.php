<?php
$roleId = session()->get('role_id'); 
$uploads_folder = base_url('public/uploads_tree/'); // Cambié 'uploads_profile' por 'uploads_tree' para que sea más representativo.
?>

<!-- Include Alpine.js for dynamic functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.4.2/dist/cdn.min.js" defer></script>

<div class="container mx-auto mt-10 text-center px-4">
    <!-- Page Header -->
    <div class="bg-white shadow-lg rounded-lg p-4 max-w-4xl mx-auto">
        <div class="text-center">
            <h1 class="text-6xl font-bold">Tree Updates</h1>
            <p class="text-gray-600 mt-2">Here is a list of all updates for the selected tree.</p>

            <!-- Navigation Button -->
            <div class="text-center mt-3">
                <?php if ($roleId == 1): ?>
                    <!-- Button for Admin Role -->
                    <a href="/adminhome" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Go to Home</a>
                <?php elseif ($roleId == 3): ?>
                    <!-- Button for Operator Role -->
                    <a href="/operatorhome" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Go to Home</a>
                <?php else: ?>
                    <!-- Button for Friend -->
                    <a href="/friend/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Go to Home</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Display Error Message -->
    <?php if (isset($error_msg)) : ?>
        <div class="bg-red-500 text-white text-center py-2 mt-4 rounded max-w-4xl mx-auto">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- Table Section -->
    <div class="mt-6 max-w-4xl mx-auto overflow-hidden">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden w-full">
            <!-- Table Header -->
            <thead>
                <tr class="bg-blue-200 text-gray-700">
                    <th scope="col" class="px-6 py-3 text-center">Tree Picture</th>
                    <th scope="col" class="px-6 py-3">Specie</th>
                    <th scope="col" class="px-6 py-3">Size</th>
                    <th scope="col" class="px-6 py-3">Update Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if there are tree updates to display -->
                <?php if (!empty($treeUpdates)) : ?>
                    <!-- Loop through each tree update -->
                    <?php foreach ($treeUpdates as $update) : ?>
                        <tr class="border-b">
                            <!-- Tree Picture -->
                            <td class="text-center px-6 py-4">
                                <?php if (!empty($update['Photo_Path'])) : ?>
                                    <?php
                                        // Generate image URL with cache-busting query
                                        $image_url = htmlspecialchars($uploads_folder . $update['Photo_Path']) . '?' . time();
                                    ?>
                                    <img src="<?= $image_url; ?>" alt="Tree Picture" class="rounded w-12 h-12 mx-auto">
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
                    <!-- Display message when no updates are found -->
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-4">No Updates found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
