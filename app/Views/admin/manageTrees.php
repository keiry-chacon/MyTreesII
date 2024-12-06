<!-- Main container for the species list page -->
<div class="container mx-auto mt-10 text-center px-4">
    <!-- Header section for the page -->
    <div class="bg-white shadow-lg rounded-lg p-4 max-w-4xl mx-auto">
        <div class="text-center">
            <!-- Page title -->
            <h1 class="text-6xl font-bold">Manage Trees</h1>
            <!-- Description of the page -->
            <p class="text-gray-600 mt-2">Here is a list of all registered trees.</p>

            <!-- Button linking to the trees registration page -->
            <div class="flex justify-center space-x-4 mt-4">
                <a href="/addtree" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Tree</a>
            </div>

            <!-- Button linking to the trees list page -->
            <div class="flex justify-center space-x-4 mt-4">
                <a href="/adminhome" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-green-600">Principal Page</a>
            </div>
        </div>
    </div>

    <!-- Display error message if there is one -->
    <?php if (isset($error_msg)) : ?>
        <div class="bg-red-500 text-white text-center py-2 mt-4 rounded max-w-4xl mx-auto">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- Table section to display the list of trees -->
    <div class="mt-6 max-w-4xl mx-auto overflow-hidden">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden w-full">
            <!-- Table header -->
            <thead>
                <tr class="bg-blue-200 text-gray-700">
                    <th class="px-6 py-3 text-center">Species</th>
                    <th class="px-6 py-3">Location</th>
                    <th class="px-6 py-3">Size</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Price</th>
                    <th class="px-6 py-3 text-center">Edit</th>
                    <th class="px-6 py-3 text-center">Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if there are any tree available to display -->
                <?php if (!empty($trees)) : ?>
                    <!-- Loop through each tree and display its details -->
                    <?php foreach ($trees as $tree) : ?>
                        <tr class="border-b">
                            <!-- Displays the species of the tree -->
                            <td class="text-center px-6 py-4">
                                <?= htmlspecialchars($tree['Commercial_Name'] . ' (' . $tree['Scientific_Name'] . ')') ?>
                            </td>                            
                            <!-- Displays the location of the tree -->
                            <td class="px-6 py-4"><?= htmlspecialchars($tree['Location']) ?></td>
                            <!-- Displays the size of the tree -->
                            <td class="px-6 py-4"><?= htmlspecialchars($tree['Size']) ?></td>
                            <!-- Displays the status of the tree as Active or Inactive -->
                            <td class="px-6 py-4">
                                <?= $tree['StatusT'] == 1 ? 'Active' : 'Inactive' ?>
                            </td>
                            <!-- Displays the price of the tree -->
                            <td class="px-6 py-4"><?= htmlspecialchars($tree['Price']) ?></td>

                            <!-- Edit link to the tree update page -->
                            <td class="px-6 py-4 text-center">
                                <a href="/updatetree?id_tree=<?= urlencode($tree['Id_Tree']) ?>" 
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 flex items-center justify-center space-x-2" 
                                title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </a>
                            </td>

                            
                            <!-- Delete form to delete the tree -->
                            <td class="px-6 py-4 text-center">
                                <form action="/admin/deletetree" method="POST" style="display:inline;" title="Delete">
                                    <input type="hidden" name="id_tree" value="<?= $tree['Id_Tree'] ?>">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 flex flex-col items-center" onclick="return confirm('Are you sure you want to delete this tree?');">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">No species found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
