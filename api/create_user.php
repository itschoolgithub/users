<?php
// Настройки подключения к базе данных
$host = 'localhost';  // Хост базы данных
$port = '3307'; // Порт базы данных
$dbname = 'mydatabase'; // Название вашей базы данных
$username = 'root'; // Имя пользователя базы данных
$password = ''; // Пароль пользователя базы данных

// Создание подключения через PDO
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Проверка, что запрос выполнен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из запроса
    $data = json_decode(file_get_contents('php://input'), true);
    $first_name = isset($data['first_name']) ? $data['first_name'] : '';
    $last_name = isset($data['last_name']) ? $data['last_name'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $role = isset($data['role']) ? $data['role'] : 'user';
    $status = isset($data['status']) ? $data['status'] : 'active';

    // Проверка на обязательные поля
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        echo json_encode(['error' => 'Все поля, кроме роли и статуса, обязательны для заполнения']);
        exit;
    }

    // SQL запрос для вставки нового пользователя в базу данных
    $sql = "INSERT INTO users (first_name, last_name, email, password, role, status, created_at, updated_at)
            VALUES (:first_name, :last_name, :email, :password, :role, :status, NOW(), NOW())";

    // Подготовка запроса
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса с привязкой параметров
    $stmt->execute([
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role,
        ':status' => $status
    ]);

    // Ответ об успешном создании пользователя
    echo json_encode(['message' => 'Пользователь успешно создан']);
} else {
    // Если запрос не POST, выводим ошибку
    echo json_encode(['error' => 'Неверный метод запроса. Используйте POST.']);
}