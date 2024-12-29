<?php
$roleId = session()->get('role_id');
?>

<!-- Main container for the species list page -->
<div class="container mx-auto mt-8 px-4">
    <!-- Header section for the page -->
    <div class="bg-green-600 shadow-lg rounded-lg p-6 max-w-5xl ml-auto mr-0">
        <div class="text-center">
            <!-- Title without animation -->
            <h1 class="text-4xl font-semibold text-white tracking-tight mb-3">
                Manage Your Friend’s Trees
            </h1>
            
            <!-- Description without animation -->
            <p class="text-white mt-2 text-base opacity-90 mb-5">
                Here is a comprehensive list of all registered trees managed by your friends. You can easily view their trees and take actions.
            </p>
        </div>
    </div>

    <!-- Display error message if there is one -->
    <?php if (isset($error_msg)) : ?>
        <div class="bg-red-500 text-white py-3 mt-6 rounded-lg max-w-5xl ml-auto text-right">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php endif; ?>

    <!-- Table section to display the list of friends -->
    <div class="mt-8 max-w-5xl ml-auto overflow-hidden">
        <div class="space-y-5">
            <!-- Check if users are available to display -->
            <?php if (!empty($users)) : ?>
                <!-- Loop through each user and display their data -->
                <?php foreach ($users as $user) : ?>
                    <div class="flex items-center justify-between space-x-4 border-b pb-4">
                        <!-- Display the user's profile picture if available -->
                        <div class="flex-shrink-0">
                            <?php if (!empty($user['Profile_Pic'])) : ?>
                                <?php
                                    // Generate the URL for the profile image with a cache-busting query string
                                    $image_url = htmlspecialchars($uploads_folder . $user['Profile_Pic']) . '?' . time();
                                ?>
                                <img src="<?= $image_url; ?>" alt="Profile Picture" class="rounded-full w-20 h-20 object-cover">
                            <?php else : ?>
                                <span class="text-gray-400">No Image</span>
                            <?php endif; ?>
                        </div>

                        <!-- User name and full name, aligned to the left with the profile image -->
                        <div class="flex flex-col ml-6">
                            <span class="font-semibold text-gray-900 text-xl"><?= htmlspecialchars($user['Username']) ?></span>
                            <span class="text-gray-600 text-sm"><?= htmlspecialchars($user['First_Name']) . ' ' . htmlspecialchars($user['Last_Name1']). ' ' . htmlspecialchars($user['Last_Name2']) ?></span>
                        </div>

                        <!-- Button to view trees, aligned to the right -->
                        <div class="flex-shrink-0 ml-auto">
                            <a href="showfriendtrees?id_user=<?= urlencode($user['Id_User']) ?>" 
                               class="bg-green-500 text-white px-5 py-2 rounded-lg hover:bg-green-600 transition-all" 
                               title="View Trees">View Trees</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="text-center text-gray-500 py-4">No Friends found</div>
            <?php endif; ?>
        </div>
    </div>
</div>

