<?php
	session_start();
	require_once "redirect.php";
	$title = "Правка пользователя";
	require_once "header.php";
?>

<div id="wrapper">
<div id="header">
	<h2>Правка таблицы пользователи</h2>
</div> 

<div id="content">
<?php
	StartDB();
	$id = $_GET['id'];
	$SQL = "SELECT * FROM пользователи WHERE `код пользователя`=".$id;

	if ($result = mysqli_query($db, $SQL))
	{
		$row = mysqli_fetch_assoc($result);
		$login  = $row['Логин'];
		$password = $row['Пароль'];
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
?>

<form action="update.php" method="post">
<?php			
		print "<input name='id' type='hidden' value=".$row['Код пользователя'].">";
	    print "<table>";
        print "<tr><td>Логин</td><td><input name='login' value='".$row['Логин']."' maxlength=60 size=30></td></tr>";
        print "<tr><td>Пароль</td><td><input name='password' value='".$row['Пароль']."'maxlength=255 size=30></td></tr>";
		mysqli_free_result($result);
?>		
     <tr><td colspan=2><input type="submit" value="Изменить"></td></tr>
    </table>
</form>

	
</div>
<div id="footer">
</div>

</div>

<?php
	require_once "footer.php";
?>
