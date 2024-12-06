<!-- Main container for the species list page -->
<div class="container mx-auto mt-10 text-center px-4">
    <!-- Header section for the page -->
    <div class="bg-white shadow-lg rounded-lg p-4 max-w-4xl mx-auto">
        <div class="text-center">
            <!-- Page title -->
            <h1 class="text-6xl font-bold">Manage Trees</h1>
            <!-- Description of the page -->
            <p class="text-gray-600 mt-2">Here is a list of all registered trees.</p>

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

    <!-- Table section to display the list of friends -->
    <div class="mt-6 max-w-4xl mx-auto overflow-hidden">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden w-full">
            <!-- Table header -->
            <thead>
                <tr class="bg-blue-200 text-gray-700">
                    <th scope="col" class="px-6 py-3 text-center">Profile Picture</th>
                    <th scope="col" class="px-6 py-3">First Name</th>
                    <th scope="col" class="px-6 py-3">Last Name</th>
                    <th scope="col" class="px-6 py-3">Username</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3 text-center">View Trees</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if users are available to display -->
                <?php if (!empty($users)) : ?>
                    <!-- Loop through each user and display their data -->
                    <?php foreach ($users as $user) : ?>
                        <tr class="border-b">
                            <!-- Display the user's profile picture if available -->
                            <td class="text-center px-6 py-4">
                                <?php if (!empty($user['Photo_Path'])) : ?>
                                    <?php
                                        // Generate the URL for the profile image with a cache-busting query string
                                        $image_url = htmlspecialchars($uploads_folder . $user['Photo_Path']) . '?' . time();
                                    ?>
                                    <!-- Display the profile image as a circular thumbnail -->
                                    <img src="<?= $image_url; ?>" alt="Profile Picture" class="rounded-full w-12 h-12 mx-auto">
                                <?php else : ?>
                                    <!-- Display a placeholder text if no image is available -->
                                    <span class="text-gray-400">No Image</span>
                                <?php endif; ?>
                            </td>
                            <!-- Display the user's first name -->
                            <td class="px-6 py-4"><?= htmlspecialchars($user['First_Name']) ?></td>
                            <!-- Display the user's last name -->
                            <td class="px-6 py-4"><?= htmlspecialchars($user['Last_Name1']) ?></td>
                            <!-- Display the user's username -->
                            <td class="px-6 py-4"><?= htmlspecialchars($user['Username']) ?></td>
                            <!-- Display the user's email -->
                            <td class="px-6 py-4"><?= htmlspecialchars($user['Email']) ?></td>

                            <!-- Edit link to the trees update page -->
                            <td class="px-6 py-4 text-center">
                                <a href="/updatetree?id_user=<?= urlencode($user['Id_User']) ?>" 
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 flex items-center justify-center space-x-2" 
                                title="View Trees">
                                    <i class="fas fa-tree"></i>
                                    <span>View Trees</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">No Friends found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
