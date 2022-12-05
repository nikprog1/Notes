<?php
	$title = "Правка таблицы";
	require_once "redirect.php";
	require_once "header.php";
?>

<div id="wrapper">
<div id="header">
	<h1>Таблица заметок <span class="badge badge-secondary"></span></h1>
</div> 

<div id="content">
	
<?php
	StartDB();
	AddNotesDB();
	EditNotesDB();
	FilterNotesDB();
	EndDB();
?>
<a href="index.php">На стартовую</br></a>
<!--
<a href="tags_info.php">К меткам</a>
-->
</div>
<div id="footer">
</div>
</div>
<?php 
	require_once "footer.php";
?>
