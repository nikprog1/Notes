<?php
	session_start();
	$title = "Задание №10";
	require_once "header.php";
?>

<?php 
	StartDB(); 
	//InitDB(); // Первоначальное создание таблиц
?>

<div id="wrapper">
	<div id="header" >
		<h1 style= "margin-bottom:-10px" > Онлайн-сервис хранения заметок </h1>
			<br>
<?php
	GetDB();
?>
			</br>
	</div> 

	<div id="content"><h3>Введите логин и пароль</h3>
	<form action="login_notes.php" method="post" >
		<p>Логин<br>
		<input name= "userlogin" size="20"
		type="text" value="" required ></p>
		<p>Пароль<br>
		<input name="userpass" size="20" type="password" value="" required></p> 
		<p><input name="login" type="submit" value="Войти"></p>
		<p> Еще не зарегистрированы?</p> 
		<a href = "register.php">Регистрация</a>
	</form>
<?php 
	if (isset($_SESSION['id']))
	{
		print "<p align=left> <a href='login_notes.php'>К таблице</a></p>";
	}
?>

<?php 
	EndPage(); require_once "footer.php";  
?>
