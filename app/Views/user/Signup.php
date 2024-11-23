<body>
    <div class="container">
        <h2>Formulario de Registro</h2>
        <form action="/signup" method="POST">
            <label for="username">Nombre de Usuario</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="dob">Fecha de Nacimiento</label>
            <input type="date" id="dob" name="dob" required>

            <label for="gender">Género</label>
            <select id="gender" name="gender" required>
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
            </select>

            <input type="submit" value="Registrar">
        </form>
    </div>
</body>