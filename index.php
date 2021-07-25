<style>
    input {
        display:block;
        margin:20px auto 0;
        width:300px;
        padding:15px;
        border-radius:10px;
        border:1px solid #ccc;
    }

    button {
        display:block;
        margin:10px auto;
        width:100px;
        padding:14px;
        background:#3baa36;
        color:#fff;
        border-radius:10px;
        border:none;
    }

    .button {
        display:block;
        margin:10px auto;
        width:300px;
        padding:14px;
        background:#efefef;
        color:#222;
        text-align:center;
        text-decoration:none;
        border-radius:10px;
        border:1px solid #3baa36;
    
    }

    .message {
        display:block;
        width:300px;
        height:22px;
        background:gold;
        text-align:center;
        padding:8px;
        margin:20px auto;
    }
</style>


<?php

// Если БД несуществует, выводим кнопку для создания БД и таблиц
try {
	$connection = new PDO('mysql:dbname=test;host=localhost', 'root', 'root');
} catch (PDOException $e) {
    echo '<a href="db_create.php" class="button">Создать базу данных и таблицы</a>';
	die();
}

// Скачивание списка записей, декодирование из Json и преобразование в ассоц. массив
$posts = file_get_contents("https://jsonplaceholder.typicode.com/posts");
$posts = json_decode($posts, true);

// Скачивание списка комментариев, декодирование из Json и преобразование в ассоц. массив
$comments = file_get_contents("https://jsonplaceholder.typicode.com/comments");
$comments = json_decode($comments, true);

// Запись постов в БД
foreach($posts as $value) {
   $connection->exec("INSERT INTO `posts` (`id`, `userId`, `title`, `body`) VALUES ('".$value['id']."', '".$value['userId']."', '".$value['title']."', '".$value['body']."')");
}

// Запись комментариев в БД
foreach($comments as $value) {
    $connection->exec("INSERT INTO `commentaries` (`id`, `postId`, `name`, `email`, `body`) VALUES ('".$value['id']."', '".$value['postId']."', '".$value['name']."', '".$value['email']."', '".$value['body']."')");
 }
?>

<script>
// Вывод сообщения в консоль браузера
console.log('<?php echo 'Загружено '.count($posts).' записей и ' .count($comments) .' комментариев'; ?>');
</script>



<form action="" method="POST">
    <input name="search" placeholder="Поиск записи по комментарию" minlength="3" required>
    <button type="submit">Найти</button>
</form>


<?php

$search = $_POST['search'];

if(!empty($search)) {

// Поиск комментария в БД
foreach($connection->query("SELECT * FROM `commentaries` WHERE body LIKE '".$search."%'") as $row) {
    // Поиск записи с id комментария
    foreach($connection->query("SELECT * FROM `posts` WHERE id='".$row['postId']."'") as $post) {
        echo '<h3>'. $post['title'] . '</h3>';
        echo $row['body'];
    }

}
}
?>



