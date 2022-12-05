<?php require_once "redirect.php"; $title = "Добавление пользователя"; require_once "header.php";
?>

<div id="wrapper">
<div id="header">
	<h2>Добавление пользователя</h2>
</div> 

<div id="content">
<?php	
	$login  = htmlspecialchars($_POST['login']);
	$password = htmlspecialchars($_POST['password']);	
	
	StartDB();

	$SQL = "INSERT INTO пользователи
					(`Логин`, `Пароль`, `Дата регистрации`) 
			VALUES 	('$login', '$password', NOW())";		
			
	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Записи в таблицу 'пользователи' добавлены.<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	print '<a href="login_notes.php">Вернуться к таблице</a>';
	
	EndDB();
?>
	
</div>
	<div id="footer">
</div>
</div>

<?php
	require_once "footer.php";
?>
