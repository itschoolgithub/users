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
    $id = isset($data['id']) ? $data['id'] : '';
    $first_name = isset($data['first_name']) ? $data['first_name'] : '';
    $last_name = isset($data['last_name']) ? $data['last_name'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $role = isset($data['role']) ? $data['role'] : 'user';
    $status = isset($data['status']) ? $data['status'] : 'active';

    // Проверка на обязательные поля
    if (empty($id) || empty($first_name) || empty($last_name) || empty($email)) {
        echo json_encode(['error' => 'Все поля, кроме пароля, обязательны для заполнения']);
        exit;
    }

    // Сохранение пароля только если он был изменен
    if (!empty($password)) {
        $password_query = ", password = :password";
        $password_param = ['password' => $password];
    } else {
        $password_query = '';
        $password_param = [];
    }

    // SQL запрос для обновления данных пользователя
    $sql = "UPDATE users SET 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email, 
            role = :role, 
            status = :status 
            $password_query, 
            updated_at = NOW() 
            WHERE id = :id";

    // Подготовка запроса
    $stmt = $pdo->prepare($sql);

    // Параметры для запроса
    $params = [
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':email' => $email,
        ':role' => $role,
        ':status' => $status,
        ':id' => $id
    ];

    // Если пароль был изменен, добавляем его в параметры
    if (!empty($password)) {
        $params[':password'] = $password;
    }

    // Выполнение запроса
    $stmt->execute($params);

    echo json_encode(['message' => 'Пользователь успешно обновлен']);
} else {
    // Если запрос не POST, выводим ошибку
    echo json_encode(['error' => 'Неверный метод запроса. Используйте POST.']);
}
