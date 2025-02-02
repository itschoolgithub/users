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

    // Проверка на наличие ID
    if (empty($id)) {
        echo json_encode(['error' => 'Не указан ID пользователя']);
        exit;
    }

    // SQL запрос для удаления пользователя по ID
    $sql = "DELETE FROM users WHERE id = :id";

    // Подготовка запроса
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса с параметром
    $stmt->execute([':id' => $id]);

    echo json_encode(['message' => 'Пользователь успешно удален']);
} else {
    // Если запрос не GET, выводим ошибку
    echo json_encode(['error' => 'Неверный метод запроса. Используйте GET.']);
}