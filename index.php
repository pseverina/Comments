<?php

	if (isset($_POST['upload'])) {

		$db = new mysqli("localhost", "root", "password", "Comments");

		$date = $_POST['date'];
		$user_name = $_POST['user_name'];
		$cellphone = $_POST['cellphone'];
		$image = $_FILES['image']['name'];
		$comments = $_POST['text'];

		$cellphone= str_replace('/[^0-9]+/', '', $cellphone);
		$img = "upload/".basename($image);

		$q = mysqli_real_escape_string($db,$image);
		$check_image = "SELECT * FROM comments where filename = '$q'";
		$result = mysqli_query($db, $check_image);
		$count = mysqli_num_rows($result);
		

		if (strlen($user_name) < 2){
			echo "Имя не может содержать меньше двух букв.";
		} elseif (!preg_match("/^([a-zA-Z']+)$/",$user_name)) {
			echo "Имя недолжно содежать цифры или спецсимволовы.";
		}elseif (strlen($cellphone) < 6) {
			echo "В номере должно содержаться минимум 6 цифр.";
		}elseif ($count > 0) {
			echo "Картинка с таким именем уже существует";
		}else {
			$sql = "INSERT INTO comments (createdAt, name, number, filename, body) VALUES ('$date', '$user_name', '$cellphone', '$image', '$comments') ";
			mysqli_query($db, $sql);
			move_uploaded_file($_FILES['image']['tmp_name'], $img);
		}


	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title> Comments project </title>
	<!-- css--> 
	<link rel="stylesheet" href="index.css" type="text/css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<!--jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

	<script >
		$(document).ready(function(){
			$('#output').load(index.php);
		});

	</script>
	<div class="container" >

		<h1> Welcome</h1>
		<form id="form" method="POST" action="index.php?submit=true" enctype="multipart/form-data">
			<div class="form-group row">
				<input  type="datetime-local" name="date" />
			</div>
			<div class="form-group row">
				<input type="user_name" name="user_name" id="user_name" placeholder="Имя" />
			</div>
			<div class="form-group row">
				<input type="tel" name="cellphone" id="cellphone" placeholder="Телефон" />
			</div>
			<div class="form-group row">
				<input type="file" class="form-control-file" id="image" name="image" />
			</div>
			<div class="form-group row">
				<textarea type="text" name="text" placeholder="Сообщение" id="text" required ></textarea>
			</div>
			<div class="form-group row">
				<input type="submit" name="upload" id="upload"/>
			</div>
		</form>
			
	<div id="output">
		<?php

			$db = new mysqli("localhost", "root", "password", "Comments");
			$sql = "SELECT * FROM comments";
			$result = $db->query($sql);
			while ($row = mysqli_fetch_array($result)) {
			echo "<div id='output'>";		
					echo "<img src='upload/".$row['filename']."' >";
					echo "<p>" .$row['createdAt']."</p>";
					echo "<p>" .$row['name']."</p>";
					echo "<p>" .$row['number']."</p>";
					echo "<p>" .$row['body']."</p>";
			echo "</div>";

			}
		?> 	
			 
		</div>
	</div>
</body>
</html>

