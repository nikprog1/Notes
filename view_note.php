<?php
	session_start();
	require_once "redirect.php";
	$title = "Отображение заметки";
	require_once "header.php";
?>

<?php
	StartDB();
	
	$id_zam=$_GET['id']; //$zagolovok  = $row['заголовок'];
	
	$SQL = "SELECT * FROM заметки WHERE `код заметки`=$id_zam";
	
	if ($result = mysqli_query($db, $SQL))
	{
		$row = mysqli_fetch_assoc($result);	
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}

	$tag="";
	
	$SQL="SELECT `заметки метки`.`код заметки`, `метки`.`метка` FROM `заметки метки` JOIN `метки` ON `метки`.`код метки`=`заметки метки`.`код метки` WHERE `код заметки`=$id_zam";
	
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
?>


<div class="card shadow-sm">
  <div class="row no-gutters">
    <div class="col-md-12">
<?php 
	if ($row['изображение']<> "")
	{
		print ' <img  src="'.$row['изображение'].'" class="card-img" style="width: 100%" alt "изображение заметки" >
		</div>';
	} 
?>
     <div class="col-md-12">
		<div class="card-body">
			<h5 class="card-title">
<?php 
	print $row['заголовок'];
?>
			</h5>
			<p class="card-text">
<?php
	print $row['текст заметки'];
?>
			</p>
			<p class="card-text">
				<small class="text-muted">
<?php 
	$tag=ShowTags(NULL, $row['код заметки']);
	
	if ($tag<> "")
	{
		print "<b>Метки: </b>".$tag; 
	}
?>  
				</small>
			</p>
		</div>
	</div>
  </div>
</div>	
</div>


<?php require_once "footer.php"; ?>




<!--
<div class="col">
          <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>

            <div class="card-body">
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">9 mins</small>
              </div>
            </div>
          </div>
        </div>
-->





