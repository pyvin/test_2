<?php

/**
 * Настройки для подключения к базе данных вынесим вверх для быстрого изменения 
 * 
 */

$host = '127.0.0.1';
$user = 'root';
$password = 'new-password';
$db = 'test_date';


/**
 * @param PDO $connection
 * @param string $ids
 * @return mixed
 */
function loadUsers($ids, $connection)
{
    $data = [];
    $id_string = explode(',', $ids);
    
    if (count($id_string) < 1) {
        return $data;
    }
    $delimiter = str_repeat('?,', count($id_string) - 1) . '?';
    $query = $connection->prepare("SELECT * FROM users WHERE id IN ($delimiter)");
    $query->execute($id_string);

    while ($user = $query->fetchObject()) {
        $data[$user->id] = $user->name;
    }

    return $data;
}

/**
 * Вынесем генерацию ссылки в отдельный метод.
 * @param $data
 */
function generalLink($data)
{
    foreach ($data as $user_id => $name) {
        echo "<a href=\"/show_user.php?id=$user_id\">$name</a> ";
    }
}

/**
 * Подключимся к базе данных один раз
 */
$connect = "mysql:dbname=$db;host=$host";

try {
    $connection = new PDO($connect, $user, $password);
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Как правило, в $_GET['user_ids'] должна приходить строка
    // с номерами пользователей через запятую, например: 1,2,17,48

    /**
     * Добавим проверку на пустое значение user_ids.
     */
    $data = loadUsers($_GET['user_ids'] ?? null, $connection);
    
    generalLink($data);

} catch (PDOException $e) {
    echo 'Fail: check logs';
}


// Не уточнен тип аргумента $user_ids
// Нет валидации входящих данных. Может переданы любые параметры, выполнение функции с которыми может вызвать ошибку со стороны БД
// Для каждого id открывается новое подключение, выполняется запрос, закрывается подключение. При большом кол-ве id будут значительные проблемы с быстродействием.
// Настройки подключения хранятся в коде функции. При изменении данных придется изменять их во всех методах, где они используются