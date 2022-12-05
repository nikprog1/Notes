<?php
	require_once "redirect.php";
	$title = "Удаление заметки";
	require_once "start_mysql.php";
	
	StartDB();
	$id = $_GET['id'];
	$SQL1 = "UPDATE `заметки` SET `удалена`=1 WHERE `код заметки`=";
	$SQL=$SQL1.$id;
	
	if (!$result = mysqli_query($db, $SQL))
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	EndDB();
	header("Location: ".$_SERVER['HTTP_REFERER']);
