<?php
// Configuración de la base de datos
$servername = "localhost";  // Esto es para XAMPP
$username = "root";         // Usuario por defecto de MySQL en XAMPP
$password = "";             // XAMPP no tiene una contraseña por defecto
$dbname = "portfolio_db"; // Nombre de la base de datos que creaste

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    // Responder con error si la conexión falla
    echo json_encode([
        'status' => 'error',
        'message' => 'Conexión fallida: ' . $conn->connect_error
    ]);
    exit; // Terminamos el script si la conexión falla
}

// Inicializamos una variable para los resultados
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Preparar y vincular la consulta SQL para insertar los datos en la base de datos
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al preparar la consulta: ' . $conn->error
        ]);
        exit;
    }
    
    $stmt->bind_param("ssss", $name, $email, $subject, $message); // "ssss" indica que los 4 parámetros son cadenas

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si se ejecutó con éxito, respondemos con éxito
        $response['status'] = 'success';
        $response['message'] = 'Mensaje enviado y guardado en la base de datos.';
    } else {
        // Si hay error, respondemos con error
        $response['status'] = 'error';
        $response['message'] = 'Error al guardar el mensaje: ' . $stmt->error;
    }

    // Cerrar la consulta preparada
    $stmt->close();
}

// Cerrar la conexión con la base de datos
$conn->close();

// Devolver la respuesta como JSON
echo json_encode($response);
?>
