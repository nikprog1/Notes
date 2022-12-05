<?php
	session_start();
	require_once "redirect.php";
	$title = "Правка таблицы";
	require_once "header.php";
?>

<div id="wrapper">
<div id="header">
	<h2>Правка таблицы пользователей</h2>
</div> 

<div id="content">
	
<?php	
	StartDB();
	EditNotesDB();
	AddNotesDB();
	EndDB();
?>
<a href="index.php">На главную</a>	
</div>
<div id="footer">
</div>

</div>

<?php
	require_once "footer.php";
?>
