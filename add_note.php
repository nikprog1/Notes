<?php
	session_start();
	require_once "redirect.php";
	$title = "Добавление заметки";
	require_once "header.php";
?>

<div id="wrapper">
	<div id="header">
		<h2>Добавление заметки</h2>
	</div> 

	<div id="content">
		
<?php
	$id_user = htmlspecialchars($_POST['id_user']);
	//$id_user = $_SESSION['id'];
	$heading = htmlspecialchars($_POST['heading']);
	$note = htmlspecialchars($_POST['note']);
	$tags = htmlspecialchars($_POST['tags']); // получение строки с метками
	$image_url = htmlspecialchars($_POST['image_url']);
	$check=file_exists($_FILES["uploadfile"]["tmp_name"]);
	//$stat_share_1 = htmlspecialchars($_POST['stat_share_1']);
	$tags=trim($tags);
	$tags=trim($tags, ",");
	if ($check==true)
{
	$image_url=ImageUpload();
}
	if ((!empty($_POST['stat_share_1'])))//поставим/снимем общий доступ
	{
		$SQL = "INSERT INTO заметки (`заголовок`,`текст заметки`,`дата создания`,`дата изменения`,`изображение`,`код пользователя`,`удалена`, `общий доступ`) 
			VALUES 	('$heading', '$note', NOW(),NOW(),'$image_url', '$id_user','0','1')";		
	}
	else
	{
		$SQL = "INSERT INTO заметки (`заголовок`,`текст заметки`,`дата создания`,`дата изменения`,`изображение`,`код пользователя`,`удалена`, `общий доступ`) 
			VALUES 	('$heading', '$note', NOW(),NOW(),'$image_url', '$id_user','0','0')";	
	}
	
	print "<b>Заголовок - $heading</b><br>";
	print "<b>Заметка - $note</b><br>";
	
	StartDB();
	//print $SQL."<br>";
	if (mysqli_query($db, $SQL) === TRUE)
	{
	//print "Записи в таблицу 'заметки' добавлены.<br>";
		$noteid=mysqli_insert_id($db);
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	print '<a href="login_notes.php">Вернуться к таблице</a>';
	
	// обработаем метки
	$notetags = explode(",",$tags);
	$notetags=array_unique($notetags);
foreach ($notetags as $tag) // поработаем с каждым тегом
{
	$tag = trim($tag); // удаляем пробелы по краям строки
	print $tag."<br>";
	$SQL = "SELECT * FROM `метки` WHERE `метка` LIKE '".$tag."' AND `код пользователя`='".$id_user."'";

	if ($result = mysqli_query($db, $SQL))
	{
		if (mysqli_num_rows($result) > 0) // работаем с существующей меткой
		{
			$row = mysqli_fetch_assoc($result);
			$tagid = $row['код метки'];
			$SQL = "INSERT INTO `заметки метки` (`код заметки`, `код метки`) VALUES ('$noteid', '$tagid')";
			mysqli_query($db, $SQL);
		}
else // если метки нет, добавляем её и затем связь
{
	$SQL = "INSERT INTO `метки` (`метка`, `код пользователя`) VALUES ('$tag', '$id_user')";
		if (mysqli_query($db, $SQL) === TRUE)
		{
			$tagid = mysqli_insert_id($db);
		}
			$SQL = "INSERT INTO `заметки метки` (`код заметки`, `код метки`) VALUES ('$noteid', '$tagid')";
			mysqli_query($db, $SQL);
		}
	}
}
// закончили обрабатывать метки
EndDB();
?>

</div>
<div id="footer">
</div>

</div>

<?php require_once "footer.php"; ?>
<?php header("Location: login_notes.php");?>
