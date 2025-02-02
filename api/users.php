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

// SQL запрос для получения всех пользователей
$sql = "SELECT * FROM users";

// Выполнение запроса и получение данных
$stmt = $pdo->query($sql);

// Получаем все записи из базы данных
$users = $stmt->fetchAll();

// Выводим данные в формате JSON
echo json_encode(['users' => $users]);