<?php
	session_start();
	require_once "redirect.php";
	$title = "Редактирование заметки";
	require_once "header.php";
?>

<div id="wrapper">
<div id="header">
	<h1>Редактирование заметки<span class="badge badge-secondary"></h1>
</div> 

<div id="content">
<?php
	StartDB();
	$id_zam=$_GET['id'];
	$stat_zam;
	$SQL = "SELECT * FROM заметки WHERE `код заметки`=$id_zam";
	if ($result = mysqli_query($db, $SQL))
	{
		$row = mysqli_fetch_assoc($result);
		//$zagolovok  = $row['заголовок'];
		//$text_zametki = $row['текст заметки'];	
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	
	$tag="";
	$SQL="SELECT `заметки метки`.`код заметки`, `метки`.`метка` FROM `заметки метки` JOIN `метки` ON `метки`.`код метки`=`заметки метки`.`код метки` WHERE `код заметки`=$id_zam AND `метки`.`удалена`='0'";
	//print $SQL;
	
	if ($result = mysqli_query($db, $SQL))
	{
		$tags_mass = mysqli_fetch_all($result);
		
		foreach ($tags_mass as list($a,$b)) 
		{
			$tag.=$b.",";
		}
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	$tag=rtrim($tag,",");

	if ($row['общий доступ']=="1") //Ставим/снимаем галочку в зависимости от значения в БД
	{
		$stat_zam="checked";
	}
	else
	{
		$stat_zam="";
	}
?>

<form action="update_notes.php" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
<?php			
		print "<input name='id' type='hidden' value=".$row['код заметки'].">";
	    print "<table>";
        print "<tr><td>Заголовок</td><td><input class='form-control' name='zagolovok' value='".$row['заголовок']."'maxlength=60 size=30 required></td></tr>";
        print "<tr><td>Текст заметки</td><td><input class='form-control' name='text_zametki' value='".$row['текст заметки']."'maxlength=255 size=30 ></td></tr>";
        print "<tr><td>Метки (через запятую)</td><td><input class='form-control' name='text_metki' value='".$tag."'maxlength=255 size=30 ></td></tr>";
        print "<tr><td>URL изображения</td><td><input class='form-control' type='url' name='image_url' value='".$row['изображение']."' maxlength=255 size=30></td></tr>";
        print "<tr><td><p>Загрузить изображение<br></td><td><input type='file' name='uploadfile'></td></tr>";
		print "<tr><td><p>Общий доступ</td> <td><input type='checkbox' ".$stat_zam." name='stat_share'></td></tr>";
?>		
     <tr><td colspan=2><input class='btn btn-primary' type="submit" value="Изменить"></td></tr>
    </table>
</form>


</div>
<div id="footer">
</div>

</div>
<?php
	require_once "footer.php";
?>

