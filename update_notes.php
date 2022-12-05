<?php session_start(); require_once "redirect.php"; $title = "Изменение заметки"; require_once "start_mysql.php"; require_once "main.php";
StartDB();
$id = $_POST['id'];//id заметки
$zagolovok  = htmlspecialchars($_POST['zagolovok']);//заголовок
$text_zametki = htmlspecialchars($_POST['text_zametki']);//текст заметки
$image_url = htmlspecialchars($_POST['image_url']);//url изображения
$text_metki = htmlspecialchars($_POST['text_metki']);//строка с метками
$stat_share = htmlspecialchars($_POST['stat_share']);//общий доступ есть/нет
$checked = htmlspecialchars($_POST['checked']);
$userid=$_SESSION['id'];//id пользователя
$check=file_exists($_FILES["uploadfile"]["tmp_name"]);

//обработка меток
$text_metki=trim($text_metki);
$notetags = explode(",",$text_metki);

//заполнение таблиц "метки" и "метки заметки"
foreach ($notetags as $tag)
{
	//проверка записи в таблицах "метки заметки" и "метки"
	$SQL="SELECT `заметки метки`.`код заметки`,`заметки метки`.`код метки` FROM `заметки метки` JOIN `метки` ON `заметки метки`.`код метки`=`метки`.`код метки` WHERE `код заметки` = $id AND `метка` LIKE '$tag'";
	if ($result = mysqli_query($db, $SQL))
	{
		if (mysqli_num_rows($result) > 0)//следующая итерация если код метки из запроса есть в таблице "метки заметки"
		{
			  continue;
		} 
		else 
		{	//вставка метки в таблицу "метки" и кода метки в таблицу "метки заметки" в случае отсутствия таковых
			$SQL="SELECT `метки`.`код метки` FROM `метки` WHERE `метка` LIKE '$tag'";	
			if ($result = mysqli_query($db, $SQL))
			{	
				if (mysqli_num_rows($result) == 0) // добавить метку если её нет в таблице
				{
					$SQL="INSERT INTO `метки`(`метка`,`код пользователя`) VALUES ('$tag','$userid')";
					mysqli_query($db, $SQL);// выполнение запроса
				}
					// добавить код метки в таблицу заметки метки
					$SQL="INSERT INTO `заметки метки`(`код метки`,`код заметки`) VALUES ((SELECT `метки`.`код метки` FROM `метки` WHERE `метка` LIKE '$tag'),'$id')";
					mysqli_query($db, $SQL);
			}
			else 
			{
				printf("Ошибка в запросе: %s\n", mysqli_error($db));
			}	
		}			
	}
	else 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
}

//удаление меток из таблицы "метки заметки", которых нет в запросе
$count=0;//счетчик
$SQL="SELECT `заметки метки`.`код метки` FROM `заметки метки` WHERE `код заметки` = $id";
if ($result = mysqli_query($db, $SQL))
{
	$id_tags_exist = mysqli_fetch_all($result);//все метки из заметки в двухмерном массиве
	$id_tags_exist=array_column($id_tags_exist,0,1);//преобразуем в одномерный нумерованный массив(столбец) для foreach
	//ищем и удаляем метки, которые отсутствуют в запросе
	foreach ($id_tags_exist as $id_tag)
	{
		$SQL="SELECT `метка` FROM `метки` WHERE `код метки`=$id_tag";
		
		if ($result = mysqli_query($db, $SQL))
		{
			$row=mysqli_fetch_assoc($result);
			foreach ($notetags as $tag)
			{
				if ($row['метка']==$tag)
				{
					$count++; //заметка есть в запросе
				}	
			}
			if ($count == 0)//удалить метку из заметки если нет её в запросе
			{
				$SQL1="DELETE FROM `заметки метки` WHERE `код метки`=$id_tag AND `код заметки`=$id ";
				mysqli_query($db, $SQL1);
			}
			$count=0;
		}
		else
		{
			printf("Ошибка в запросе: %s\n", mysqli_error($db));
		}
	}
}
else
{
	printf("Ошибка в запросе: %s\n", mysqli_error($db));
}
	
	
	
if ($check==true)
{
$image_url=ImageUpload();
}
if ($stat_share=='on')//поставим/снимем общий доступ
{
$SQL = "UPDATE `заметки` SET `заголовок`='$zagolovok', `текст заметки`='$text_zametki', `дата изменения`=NOW(),`изображение`='$image_url', `общий доступ`='1' WHERE `код заметки`=$id";
}
else
{
$SQL = "UPDATE `заметки` SET `заголовок`='$zagolovok', `текст заметки`='$text_zametki', `дата изменения`=NOW(),`изображение`='$image_url', `общий доступ`='0' WHERE `код заметки`=$id";
}

//$SQL=$SQL1.$id;
if (!$result = mysqli_query($db, $SQL))
{
printf("Ошибка в запросе: %s\n", mysqli_error($db));
}

EndDB();
header("Location: login_notes.php");
