<h1>Profile</h1>
<img src="<?= base_url('uploads_profile/' . $user['Profile_Pic']); ?>" alt="Profile Picture">
<p>Name: <?= $user['First_Name'] . ' ' . $user['Last_Name1'] . ' ' . $user['Last_Name2']; ?></p>
<p>Email: <?= $user['Email']; ?></p>
<p>Phone: <?= $user['Phone']; ?></p>
<p>Gender: <?= $user['Gender']; ?></p>
<p>District: <?= $user['District_Id']; ?></p>

<a href="<?= base_url('/profile/edit'); ?>">Edit Profile</a>
