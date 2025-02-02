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

// Проверка, что запрос выполнен методом GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получаем ID пользователя из GET-запроса
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    // Проверка на наличие ID
    if (empty($id)) {
        echo json_encode(['error' => 'Не указан ID пользователя']);
        exit;
    }

    // SQL запрос для получения данных пользователя по ID
    $sql = "SELECT * FROM users WHERE id = :id";

    // Подготовка запроса
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса с параметром
    $stmt->execute([':id' => $id]);

    // Получаем данные пользователя
    $user = $stmt->fetch();

    if ($user) {
        // Если пользователь найден, выводим данные в формате JSON
        echo json_encode(['user' => $user]);
    } else {
        // Если пользователь не найден, возвращаем ошибку
        echo json_encode(['error' => 'Пользователь не найден']);
    }
} else {
    // Если запрос не GET, выводим ошибку
    echo json_encode(['error' => 'Неверный метод запроса. Используйте GET.']);
}