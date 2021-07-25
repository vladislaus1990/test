<!DOCTYPE html> 
<html>
<head> 
<meta charset="UTF-8"> 
<title>...</title> 
<link rel="stylesheet" type="text/css" href="style.css"> 
<script src="https://use.fontawesome.com/179054da48.js"></script>
</head>
<body> 

<?php
// Проверка существования БД. Если БД несуществует, выводим кнопку для создания БД и таблиц
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

echo '<p class="count"><i class="fa fa-database" aria-hidden="true"></i> В базе данных ' .count($posts). ' записей и ' .count($comments ). ' комментариев</p>';

?>

<script>
// Вывод сообщения в консоль браузера
console.log('<?php echo 'Загружено '.count($posts).' записей и ' .count($comments) .' комментариев'; ?>');
</script>



<form action="" method="POST" class="form">
    <input name="search" placeholder="Поиск записи по комментарию" required>
    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i> Найти</button>
</form>


<?php

$search = $_POST['search'];

if(!empty($search)) {
    
    if(strlen($search) >= 3) {
        
        foreach($result = $connection->query("SELECT * FROM `commentaries` WHERE body LIKE '".$search."%'") as $row) {
            
            foreach($connection->query("SELECT * FROM `posts` WHERE id='".$row['postId']."'") as $post) {
                echo '<h2>'. $post['title'] . '</h2>';
                echo '<i class="fa fa-comment-o" aria-hidden="true"></i> '.$row['body'] .'<hr>';
    }
}

echo '<br><h3 align="center">Найдено ' .$result->rowCount(). ' совпадений</h3>';

} else {
    echo '<p class="error">Введите текст длиной более 3 символов!</p>';
}
}


?>

</body>
</html>








