<?php
$uploads_tree = base_url('/uploads_tree/');
$data['uploads_tree'] = $uploads_tree;

$roleId = session()->get('role_id'); 

?>

<!-- Main container for the Tree Management page -->
<div class="container mx-auto mt-10 text-center px-4">
    <!-- Header section for the Tree Management title -->
    <div class="bg-white shadow-lg rounded-lg p-4 max-w-4xl mx-auto mb-5">
        <div class="text-center">
            <h1 class="text-6xl font-bold">Tree Management</h1>
            <p class="text-gray-600 mt-2">List of all trees associated with this friend</p>
            <!-- Link to go back -->
            <?php if ($roleId == 1): ?>
                <!-- Enlace para Admin -->
                <a href="/adminhome" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-3 inline-block">Go to Home</a>
            <?php else: ?>
                <!-- Enlace para otros roles (Operator) -->
                <a href="/operatorhome" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-3 inline-block">Go to Home</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Display error message if there is one -->
    <?php if (isset($error_msg)) : ?>
        <div class="bg-red-500 text-white text-center py-2 mt-4 rounded max-w-4xl mx-auto">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- If there are no trees, display a message saying so -->
    <?php if (empty($trees)): ?>
        <div class="bg-blue-100 text-blue-600 text-center py-4 rounded-lg shadow-md max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold">Oh no!</h2>
            <p class="mt-2 text-xl">It looks like this friend doesn't have any trees yet.</p>
            <p class="mt-4 text-gray-600">Maybe they’ll have some soon. Stay tuned!</p>
        </div>
    <?php else: ?>
        <!-- Grid layout for displaying the trees if available -->
        <div class="mt-6 max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loop through each tree to display details -->
            <?php foreach ($trees as $tree):
                // Set the image path with cache-busting using time()
                $photoTree              = $uploads_tree . $tree['Photo_Path']. '?' . time();
                // URLs for updating the tree or registering an update
                $updateFriendTree       = "/updatefriendtree?id_tree=" . $tree['Id_Tree']; 
                $registerUpdate         = "/registerupdate?id_tree=" . $tree['Id_Tree']; 
                $treeHistory            = "/treehistory?id_tree=" . $tree['Id_Tree']; 

            ?>
                <!-- Card for each tree -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Display the tree's photo -->
                    <img src="<?php echo $photoTree; ?>" alt="Tree Picture" class="w-full h-32 object-contain mx-auto">
                    <div class="p-4">
                        <!-- Display the tree's commercial name -->
                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($tree['Commercial_Name']); ?></h3>
                        <!-- Display the tree's location -->
                        <p class="text-gray-600">Location: <?php echo htmlspecialchars($tree['Location']); ?></p>
                        <!-- Display the tree's purchase date -->
                        <p class="text-gray-600">Purchase date: <?php echo htmlspecialchars(date('Y-m-d', strtotime($tree['Purchase_Date']))); ?></p>
                        <!-- Buttons for updating the tree or registering an update -->
                        <div class="mt-4 flex justify-between">
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
    <?php endif; ?>
</div>