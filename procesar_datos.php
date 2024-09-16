<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

   
    if ($password != $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    
    if (empty($username) || empty($email) || empty($password)) {
        echo "Todos los campos son obligatorios.";
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


    $data_array[] = $data;

    file_put_contents($file, json_encode($data_array, JSON_PRETTY_PRINT));

  
    header('Location: success.html');
    exit;
}
?>
