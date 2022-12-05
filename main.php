<?php

// Создание таблиц
function InitDB()
{
    global $db;
    // Таблица "заметки"
    if (mysqli_query($db, "DROP TABLE IF EXISTS заметки;") === TRUE)
    {
	print "<br>Таблица заметки удалена<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }
    
    $SQL = "CREATE TABLE заметки (
    `код заметки` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    `заголовок` VARCHAR(100) NOT NULL,
    `текст заметки` VARCHAR(2000) NOT NULL,
    `дата создания` DATE NOT NULL,
    `дата изменения` DATE NOT NULL,
    `изображение` VARCHAR(300) NOT NULL,
    `общий доступ` INT(11) NOT NULL,
    `удалена` INT(11) NOT NULL,
    `код пользователя` INT(11) NOT NULL);";
    
    if (mysqli_query($db, $SQL) === TRUE)
    {
	print "Таблица заметки создана<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }
    
    // Таблица "заметки метки"
    if (mysqli_query($db, "DROP TABLE IF EXISTS `заметки метки`;") === TRUE)
    {
	print "<br>Таблица заметки метки удалена<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }

    $SQL = "CREATE TABLE `заметки метки` (
    `код записи` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    `код заметки` INT(10) NOT NULL,
    `код метки` INT(10) NOT NULL,
    `удалена` INT(10) NOT NULL DEFAULT '0');";

    if (mysqli_query($db, $SQL) === TRUE)
    {
	print "Таблица заметки метки создана<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }

    // Таблица "метки"
    if (mysqli_query($db, "DROP TABLE IF EXISTS метки;") === TRUE)
    {
	print "<br>Таблица метки удалена<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }

    $SQL = "CREATE TABLE метки (
    `код метки` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    `метка` VARCHAR(100) DEFAULT NULL,
    `код пользователя` INT(10) NOT NULL,
    `удалена` INT(10) NOT NULL DEFAULT '0');";

    if (mysqli_query($db, $SQL) === TRUE)
    {
	print "Таблица метки создана<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }

    // Таблица "пользователи"
    if (mysqli_query($db, "DROP TABLE IF EXISTS пользователи;") === TRUE)
    {
	print "<br>Таблица пользователи удалена<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }

    $SQL = "CREATE TABLE пользователи (
    `Код пользователя` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    `Логин` VARCHAR(100) NOT NULL,
    `Пароль` VARCHAR(300) NOT NULL,
    `Дата регистрации` DATE NOT NULL);";

    if (mysqli_query($db, $SQL) === TRUE)
    {
	print "Таблица пользователи создана<br>";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }
}


// Подсчет количества пользователей
function GetDB()
{
    global $db;
    
    $SQL = "SELECT * FROM пользователи";

    if ($result = mysqli_query($db, $SQL))
    {
	/*print "<table border=1 cellpadding=10>";
	print "<tr><td><b>Логин</b></td><td><b>Пароль</b></td></tr>";
	// Выборка результатов запроса
	while( $row = mysqli_fetch_assoc($result) )
	{
	print "<tr>";
	printf("<td>%s</td><td>%s</td>", $row['Логин'], $row['Пароль']);
	print "</tr>";
	}
	print "</table>"; */
	$usercount=mysqli_num_rows($result);
	print 'Количество пользователей:'.$usercount;
	mysqli_free_result($result);
    }
    else
    {
	printf("Ошибка в запросе: %s\n", mysqli_error($db));
    }

}

// Вывод формы для добавления пользователя
function AddNotesDB()
{
    global $db;
    
    // Получение списка пользователей
    $SQL = "SELECT * FROM заметки";
    
    if (!$result = mysqli_query($db, $SQL))
    {
	printf("Ошибка в запросе: %s\n", mysqli_error($db));
    }
    
    $id_user= $_SESSION['id'];
    ?>
    
    <form action="add_note.php" enctype="multipart/form-data" method="post">
	<input type="hidden" name="id_user" value =<?php print $id_user ?>>
	<table>
	    <tr><td>Заголовок</td><td>
		<input class='form-control'  name="heading" size=51 required ></td></tr>
	    <tr><td><p>Текст заметки<br></td><td>
		<textarea class='form-control' name="note" cols="40" rows="3"></textarea></p></td></tr>
	    <tr><td>Метки (через запятую)</td><td>
		<input class='form-control' name="tags" size="50"><td></tr>
	    <tr><td><p>URL изображения<br></td><td>
		<input class='form-control' type="url" name="image_url" size=55><input type="file" name="uploadfile"></td></tr>
	    <tr><td><p>Общий доступ</td> <p><td>
		<input type='checkbox' name='stat_share_1'></td></tr>
	    <tr><td colspan=2>
		<input class='btn btn-primary' type="submit" value="Добавить"></td></tr>
	</table>
    </form>
    <?php
}

// Вывод таблицы с функциями редактирования
function EditNotesDB()
{
    global $db;
    $id_user=$_SESSION['id']; // Переменная сессии
    $search_sql = '';
    
    if(isset($_GET['search']))// Поисковое словосочетание для поиска по заметкам
    {
	$search=$_GET['search'];
	$search_sql = "AND (`заголовок` LIKE '%".$search."%' OR `текст заметки` LIKE '%".$search."%')";
    }
    else $search = '';

    if(isset($_GET['id_zam']))// Код метки
    {
	$id_zam=$_GET['id_zam'];
	$SQL="SELECT `заметки`.`код заметки`,`заметки`.`заголовок`,`заметки`.`текст заметки`,`заметки`.`дата создания`,`заметки`.`дата изменения`,`заметки`.`изображение`,`заметки`.`общий доступ` 
	FROM `заметки` JOIN `заметки метки` ON `заметки`.`код заметки`=`заметки метки`.`код заметки` 
	JOIN `метки` ON `заметки метки`.`код метки`=`метки`.`код метки` 
	WHERE `заметки`.`удалена`='0' 
	AND `заметки`.`код пользователя`=$id_user AND `заметки метки`.`код метки`=$id_zam";
    }
    else 
	$SQL="SELECT * FROM `заметки` 
	WHERE `удалена`='0' AND `код пользователя`='$id_user' $search_sql 
	ORDER BY `дата изменения` DESC";
    
    //print $SQL;
    $result=mysqli_query($db,$SQL);
    $num_notes=mysqli_num_rows($result);
    //пагинация;
    $per_page=5;
    
    if (isset($_GET['page']))
    {
	$page = ($_GET['page']-1);
    }
    else
    {
	$page=0;
    }
    
    //вычисляем первый оператор для LIMIT
    $start=$page*$per_page;
    $limit_sql=" LIMIT $start,$per_page";
    $SQL=$SQL.$limit_sql;
    //print $SQL;

    if ($result = mysqli_query($db, $SQL))
    {
	print "<table  class='table table-striped' border=1cellpadding=10  ><tr><td align=center ><b>Заголовок</b></td><td  align=center width='500px' ><b>Текст заметки</b></td><td align=center><b>Изображение</b><td align=center> <b>Метка</b></td></td><td align=center><b>Дата создания</b></td><td align=center> <b>Дата изменения</b></td><td colspan=2 align=center><b>Действие</b></td><td colspan=2 align=center><b>Общий доступ</b></td></tr>";
	while ($row = mysqli_fetch_assoc($result))
	{
	    
	    $image_url = $row['изображение'];
	    
	    if ($row['общий доступ']=="1") //Считаем статус общего доступа заметки
	    {
		$share_to_show="Да";
	    }
	    else
	    {
		$share_to_show="Нет";
	    }
	    
	    if ($image_url == "")
	    {
		$imagehtml = "";
	    }
	    else
	    {
		$imagehtml = "<a href=".$image_url." target=_blank><img width=150 src='".$image_url."'></a>";
	    }
	    
	    print "<tr>";
	    printf("<td>%s</td><td>%s</td><td>$imagehtml</td><td>"
	    .ShowTags($id_user,$row['код заметки']).
	    "</td><td>%s</td><td>%s</td>",$row['заголовок'],chunk_split_unicode($row['текст заметки'],30),$row['дата создания'], $row['дата изменения']);
	    print "<td><a href='edit_notes.php?id=".$row['код заметки']."'>Изменить</a><br><a href='view_note.php?id=".$row['код заметки']."'>Просмотр</a></td>";
	    print "<td><a href='delete_notes.php?id=".$row['код заметки']."'>Удалить</a></td>";
	    print "<td align='center' style='vertical-align:middle;'><a >$share_to_show</a></td>";
	    print "</tr>";
	}
	
	print "</table><br>";
	print "Количество заметок ".$num_notes."</br> Страницы:";
    
	$num_pages=ceil($num_notes/$per_page);
    
	for ($i=1;$i<=$num_pages;$i++)
	{
	    if ($i-1 == $page)
	    {
		print "<b>[".$i."]</b>";
	    }
	    else
	    {
		print '<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'&search='.$search.'">['.$i.']</a>';
	    }
	}
    }
}

//Вывод таблицы с метками
function tags_info()
{
    global $db;
    $id_user=$_SESSION['id'];
    print "<table  class='table table-striped' border=1cellpadding=10><tr><td align=center ><b>Метка</b></td><td align=center ><b>Число связанных заметок</b></td></tr>";
    $SQL="SELECT `метка` ,`метки`.`код метки`, COUNT(*) AS zametki FROM `заметки` JOIN `заметки метки` ON `заметки`.`код заметки`=`заметки метки`.`код заметки` JOIN `метки` ON `заметки метки`.`код метки`=`метки`.`код метки` WHERE `заметки`.`удалена`='0' GROUP BY `метка`";
    if ($result = mysqli_query($db, $SQL))
    {
	while($row=mysqli_fetch_assoc($result))
	{
	    if ($row['метка']=='')
	    {
		$metka="без метки";
	    }
	    else
	    {
		$metka=$row['метка'];
	    }
	    print "<tr><td><a href='login_notes.php?id_zam=".$row['код метки']."'>".$metka."<br></a></td><td>".$row['zametki']."<br></td></tr>";
	}
	print"</table><br>";
    }
    else
    {
	printf("Ошибка в запросе: %s\n", mysqli_error($db));
    }
}

//Вывод формы поискового запроса
function FilterNotesDB()
{
    if(isset($_GET['search']))
    {
	$search=$_GET['search'];
    }
    else {
	$search = '';
    }

    print '<form action="login_notes.php" method="get">
	<p>Поиск<br>
	    <input name="search" size="20" type="text" value="'.$search.'" ></p>
	<p><input name="search_but" type="submit" value="Найти"><input type="reset" ></p>
    </form>';
}

function StartPage()
{
?>
<div id="wrapper">
<div id="header">
</div>

<div id="content">
<?php

}

function EndPage()
{
?>
</div>
<div id="footer">
</div>
</div>
<?php
}

// Проверка авторизации
function CheckLogin()
{
    if (isset($_SESSION['id']))//Проверка залогинивания пользователя
    {
	require "edit_table.php";
    }
    else
    {
	//Проверка логина
	if(isset($_POST['userlogin']))
	{
	    $_SESSION['login'] = $_POST['userlogin'];
	    $_SESSION['password'] = $_POST['userpass'];
	    //print "<br> Логин ".$_SESSION['login'];
	    if(CheckPassword())
	    {
		require "edit_table.php";
		//return TRUE;
	    }
	    else
	    {
		require_once "index.php";
	    }
	}
    }
}

//Проверка пароля
function CheckPassword()
{
    $a;
    global $db;

    // Составляем строку запроса
    $SQL = "SELECT * FROM `пользователи` WHERE `Логин` LIKE '".$_SESSION['login']."'";
    
    if ($result = mysqli_query($db, $SQL))
    {
	// Если нет пользователя с таким логином, то завершаем функцию
	if(mysqli_num_rows($result)==0)
	{
	    //	print "Нет такого логина<br>";
	    return FALSE;
	}
	
	// Если логин есть, то проверяем пароль
	$row = mysqli_fetch_assoc($result);
	
	if (password_verify($_SESSION['password'], $row['Пароль']))
	{
	    //print "<br>Пароль совпадает<br>";
	    $_SESSION['id']=$row['Код пользователя'];
	    return TRUE;
	}
    }
    else
    {
	//print "<br>Нет такого пароля";
	return FALSE;
    }

}

// Функция регистрации пользователя
function RegUser()
{
    global $db;
    
    // Проверка данных
    if(!$_POST['user_login'])
    {
	print "<br>Не указан логин";
	return FALSE;
    }
    elseif(!$_POST['user_password'])
    {
	print "<br>Не указан пароль";
	return FALSE;
    }

    // Проверяем не зарегистрирован ли уже пользователь
    $SQL = "SELECT `Логин` FROM `пользователи` WHERE `Логин` LIKE '".$_POST['user_login']. "'";

    // Делаем запрос к базе
    if ($result = mysqli_query($db, $SQL))
    {
	// Если есть пользователь с таким логином, то завершаем функцию
	if(mysqli_num_rows($result) > 0)
	{
	    print "<br>Пользователь с указанным логином уже зарегистрирован.";
	    return FALSE;
	}
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
    }
    
    // Если такого пользователя нет, регистрируем его
    $hash_pass = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
    $SQL = "INSERT INTO `пользователи`(`Логин`,`Пароль`,`Дата регистрации`) VALUES ('".$_POST['user_login']. "','".$hash_pass. "', NOW())";

    if (mysqli_query($db, $SQL) === TRUE)
    {
	//print "<br>Пользователь зарегистрирован";
    }
    else
    {
	printf("Ошибка: %s\n", mysqli_error($db));
	return FALSE;
    }
    return TRUE;
}

// загрузка изображения
function ImageUpload()
{
    @mkdir("files", 0777); // создаем папку, если ее нет то ошибки не будет, задаем права
    $uploaddir = 'files/';
    $uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);
    if(copy($_FILES['uploadfile']['tmp_name'], $uploadfile))
    {
	$url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url = explode('?', $url);
	$url = $url[0];
	$array = parse_url($url);
	$arr_path = explode('/', $array['path']);
	$url = $array['scheme'].'://'.$array['host'].'/'.$arr_path[1];
	$imageurl = $url."/files/".$_FILES['uploadfile']['name'];
	//   echo "<h3>Файл успешно загружен на сервер</h3>".$imageurl;
	//   echo "<img src='".$imageurl."'>";
    }
    else
    {
	// echo "<h3>Не удалось загрузить файл на сервер</h3>";
	return FALSE;
    }

    //Данные о загруженном файле

    /* echo "<h3>Информация о загруженном на сервер файле: </h3>";

    echo "<p>Оригинальное имя загруженного файла:<b> ".$_FILES['uploadfile']['name']."</b></p>";

    echo "<p>Mime-тип загруженного файла:<b> ".$_FILES['uploadfile']['type']."</b></p>";

    echo "<p>Размер загруженного файла в байтах:<b> ".$_FILES['uploadfile']['size']."</b></p>";

    echo "<p>Временное имя файла: <b>".$_FILES['uploadfile']['tmp_name']."</b></p>";

    */

    return $imageurl;

}

// показать метки
function ShowTags($userid, $noteid = NULL)
{
    global $db;
    if ($noteid == NULL)
    {
	$SQL = "SELECT * FROM метки WHERE `код пользователя`=".$userid;
	if ($result = mysqli_query($db, $SQL))
	{
	    //$tags_html = "";
	    while ($row = mysqli_fetch_assoc($result))
	    {
		$tags_html = $row['метка']." ".$tags_html;
	    }
	}
	return $tags_html;
    }
    else
    {
	$SQL = "SELECT `метки`.`метка` FROM `метки` JOIN `заметки метки` ON `метки`.`код метки` = `заметки метки`.`код метки` WHERE `заметки метки`.`код заметки` = $noteid AND `заметки метки`.`удалена`='0'";
	if ($result = mysqli_query($db, $SQL))
	{
	    $tags_html = "";
	    while ($row = mysqli_fetch_assoc($result))
	    {
		$tags_html = "<a href='tags_info.php'>".$row['метка']." ".$tags_html."</a>";
	    }
	}
	return $tags_html;
    }
}

function chunk_split_unicode($str, $l = 25, $e = "<br/>")
{
    $tmp = array_chunk( preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $l);
    $str = "";
    foreach ($tmp as $t) 
    {
	$str .= join("", $t) . $e;
    }
    return $str;
}





