<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($password != $confirm_password) {
        echo json_encode(['error' => 'Las contraseñas no coinciden.']);
        exit;
    }

    
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
        exit;
    }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Formato de correo inválido.']);
        exit;
    }

    
    $data = [
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    
    $file = 'usuarios.json';
    if (file_exists($file)) {
        $json_data = file_get_contents($file);
        $data_array = json_decode($json_data, true);
    } else {
        $data_array = [];
    }

    
    foreach ($data_array as $user) {
        if ($user['email'] === $email) {
            echo json_encode(['error' => 'Este correo ya está registrado.']);
            exit;
        }
    }

    
    $data_array[] = $data;

    
    if (file_put_contents($file, json_encode($data_array, JSON_PRETTY_PRINT))) {
        
        echo json_encode(['success' => 'Usuario registrado exitosamente.']);
    } else {
       
        echo json_encode(['error' => 'Hubo un problema al guardar los datos.']);
    }

    exit;
} else {
    
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}
?>
