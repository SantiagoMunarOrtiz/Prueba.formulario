<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Capturar los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validación de contraseñas coincidentes
    if ($password != $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Validar campos vacíos
    if (empty($username) || empty($email) || empty($password)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de correo inválido.";
        exit;
    }

    // Hashing de la contraseña
    $data = [
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT) // Se usa bcrypt por defecto
    ];

    // Manejo del archivo JSON para guardar los datos de usuario
    $file = 'usuarios.json';
    if (file_exists($file)) {
        $json_data = file_get_contents($file);
        $data_array = json_decode($json_data, true);
    } else {
        $data_array = [];
    }

    // Verificar si el correo ya está registrado
    foreach ($data_array as $user) {
        if ($user['email'] === $email) {
            echo "Este correo ya está registrado.";
            exit;
        }
    }

    // Agregar el nuevo usuario
    $data_array[] = $data;

    // Guardar los datos en el archivo JSON
    file_put_contents($file, json_encode($data_array, JSON_PRETTY_PRINT));

    // Redirigir al usuario a la página de éxito
    header('Location: success.html');
    exit;
}
?>
