<?php session_start(); require_once "redirect.php"; 
$title = "Таблица меток"; require_once "header.php";
?>

<div id="wrapper">
<div id="header">
	<h1>Таблица меток <span class="badge badge-secondary"></span></h1>
</div> 

<div id="content">

<?php	
	StartDB();
	tags_info();
	EndDB();
?>

</div>
<div id="footer">
</div>
</div>

<?php require_once "footer.php";?>


