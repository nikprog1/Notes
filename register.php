<?php session_start(); $title = "Вход в систему"; require_once "header.php"; StartPage(); ?>


<?php
// Если была нажата кнопка регистрации
if(isset($_POST['register'])) 
{
// E-mail
$email = $_POST['user_login'];
// Подтверждаем, что E-mail верен
if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
{
	// Проверяем совпадение паролей
	if ($_POST['user_password'] === $_POST['password_again']) 
	{
		// Регистрация пользователя
		StartDB();
		$res = RegUser();
		EndDB();
		
		if($res)
		{
			print "<br>Вы успешно зарегистрировались в системе."; 
			print "<br>Сейчас вы будете переадресованы к странице авторизации."; 
			print "<br>Если это не произошло, перейдите на неё по <a href='index.php'>прямой ссылке</a>.</p>";
			print '<meta http-equiv="refresh" content="5;URL=login.php">';
		}
		else
		{
			print "<br>Во время регистрации произошли ошибки."; 
		}		
	}
	else
	{
		print "<br>Введенные пароли не совпадают.";
	}	
}
else
	{
		print ("Логин $email не является E-mail адресом");
	}
}
?>

<h1>Регистрация</h1>

<form action="register.php" method="post">
	<p>Логин(email)<br><input name="user_login" size="20" type="text"></label></p>
	<p>Пароль<br><input name="user_password" size="20"  type="password" value = ''></label></p>
	<p>Повторите пароль<br><input name="password_again" size="20" type="password" value = ''></label></p>
	<p><input   name = "register" type="submit" value="Зарегистрироваться"></p>
	<p>Уже зарегистрированы?</p> 
	<a href= "index.php">На главную</a>
 </form>
 
 <?php EndPage(); require_once "footer.php";  ?>
