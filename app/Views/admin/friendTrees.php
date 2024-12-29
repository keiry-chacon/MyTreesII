<?php
$uploads_tree = base_url('/uploads_tree/');
$data['uploads_tree'] = $uploads_tree;

$roleId = session()->get('role_id'); 

?>
<!-- Back Arrow -->
<div class="mt-4 flex justify-start">
    <a href="javascript:history.back()" class="text-blue-600 text-2xl hover:text-blue-800 transition-all duration-300 flex items-center ml-80">
        <i class="fas fa-arrow-left mr-2"></i>
    </a>
</div>
<!-- Main container for the Tree Management page -->
<div class="container mr-0 mt-10 px-4">
    <!-- Display error message if there is one -->
    <?php if (isset($error_msg)) : ?>
        <div class="bg-red-500 text-white py-2 mt-4 rounded max-w-4xl ml-auto text-right">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- If there are no trees, display a message saying so -->
    <?php if (empty($trees)): ?>
        <div class="bg-blue-100 text-blue-600 text-center py-4 rounded-lg shadow-md max-w-4xl ml-80">
            <h2 class="text-2xl font-semibold">Oh no!</h2>
            <p class="mt-2 text-xl">It looks like this friend doesn't have any trees yet.</p>
            <p class="mt-4 text-gray-600">Maybe they’ll have some soon. Stay tuned!</p>
        </div>
    <?php else: ?>
        <!-- Contenedor principal con flexbox para alinear la cuadrícula a la derecha -->
        <div class="mt-6 max-w-4xl ml-80 flex justify-end">
            <!-- Grid layout para las cartas de los árboles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Loop a través de cada árbol para mostrar los detalles -->
                <?php foreach ($trees as $tree):
                    // Set the image path with cache-busting using time()
                    $photoTree              = $uploads_tree . $tree['Photo_Path']. '?' . time();
                    // URLs for updating the tree or registering an update
                    $updateFriendTree       = "/admin/showupdatefriendtree?id_tree=" . $tree['Id_Tree']; 
                    $registerUpdate         = "showregisterupdate?id_tree=" . $tree['Id_Tree']; 
                    $treeHistory            = "showtreehistory?id_tree=" . $tree['Id_Tree'];
                ?>
                    <!-- Card for each tree -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <!-- Display the tree's photo -->
                        <img src="<?php echo $photoTree; ?>" alt="Tree Picture" class="w-full h-32 object-contain mx-auto">
                        <div class="p-4">
                            <!-- Display the tree's commercial name -->
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($tree['Commercial_Name']); ?></h3>
                            <!-- Display the tree's location -->
                            <p class="text-gray-600">Location: <?php echo htmlspecialchars($tree['Shipping_Location']); ?></p>
                            <!-- Display the tree's purchase date -->
                            <p class="text-gray-600">Purchase date: <?php echo htmlspecialchars(date('Y-m-d', strtotime($tree['Purchase_Date']))); ?></p>
                            <!-- Buttons for updating the tree or registering an update -->
                            <div class="mt-4 flex justify-between space-x-4"> <!-- Agregamos espacio entre los botones -->
                                <?php 
                                    // Verificamos el rol y mostramos los botones correspondientes
                                    if ($roleId == 1): ?> 
                                        <!-- Mostrar ambos botones para el rol 1 (Admin) -->
                                        <a href="<?php echo $updateFriendTree; ?>" class="bg-yellow-500 text-white px-1 py-2 rounded hover:bg-yellow-600">Update Tree</a>
                                        <a href="<?php echo $registerUpdate; ?>" class="bg-green-500 text-white px-1 py-2 rounded hover:bg-green-600">Register Update</a>
                                        <a href="<?php echo $treeHistory; ?>" class="bg-green-500 text-white px-1 py-2 rounded hover:bg-green-600">Tree History</a>
                                    <?php else: ?>
                                        <div class="w-full text-center">
                                            <a href="<?php echo $registerUpdate; ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Register Update</a>
                                            <a href="<?php echo $treeHistory; ?>" class="bg-green-500 text-white px-1 py-2 rounded hover:bg-green-600">Tree History</a>
                                        </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>