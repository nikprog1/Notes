<?php require_once "redirect.php"; $title = "Изменение пользователя"; require_once "start_mysql.php";
	StartDB();
	$id = $_POST['id'];
	$login  = htmlspecialchars($_POST['login']);
	$password = htmlspecialchars($_POST['password']);
	$SQL = "UPDATE пользователи SET `Логин`='$login', `Пароль`='$password' WHERE `Код пользователя`='$id'";

	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	EndDB();
	header("Location: login_notes.php");
