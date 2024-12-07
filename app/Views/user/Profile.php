<body class="bg-gray-900 text-white">

<div class="container">
    <h1>Perfil de Usuario</h1>
    <form action="<?= site_url('user/updateProfile') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Imagen de perfil -->
        <div>
        <img src="<?= $uploads_profile . $profile . '?' . time() ?>" alt="Profile Image" class="w-20 h-20 rounded-full border-4 border-white mb-3 object-cover">
        <input type="file" name="profileImage" accept="image/*">
        </div>

        <!-- Datos del usuario -->
        <div>
            <label>Nombre de usuario</label>
            <input type="text" name="username" value="<?= esc($userData['Username']) ?>" required>
        </div>
        <div>
            <label>Nombre</label>
            <input type="text" name="first_name" value="<?= esc($userData['First_Name']) ?>" required>
        </div>
        <div>
            <label>Primer apellido</label>
            <input type="text" name="last_name1" value="<?= esc($userData['Last_Name1']) ?>" required>
        </div>
        <div>
            <label>Segundo apellido</label>
            <input type="text" name="last_name2" value="<?= esc($userData['Last_Name2']) ?>">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= esc($userData['Email']) ?>" required>
        </div>
        <div>
            <label>Teléfono</label>
            <input type="text" name="phone" value="<?= esc($userData['Phone']) ?>" required>
        </div>
        <div>
            <label>Género</label>
            <select name="gender">
                <option value="M" <?= $userData['Gender'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= $userData['Gender'] == 'F' ? 'selected' : '' ?>>Femenino</option>
            </select>
        </div>
        <div>
            <label>Nueva contraseña</label>
            <input type="password" name="password">
        </div>
        <button type="submit">Actualizar Perfil</button>
    </form>
</div>
</body>

