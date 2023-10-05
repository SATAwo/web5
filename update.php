<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="./Lab1-2.css" />
</head>

<body>
    <?php
	date_default_timezone_set('Europe/Moscow');

	function debug_to_console($data) {
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);

		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}

	function checkTitleValidity($value) {
		$value = trim($value);

		if (empty($value)) {
			return "Поле пустое!";
		}
		elseif (strlen($value) < 4) {
			return "Минимум 4 символа!";
		}
		elseif (strlen($value) > 120) {
			return "Максимум 30 символов!";
		}
		else if (!preg_match("/^[a-z\x{0410}-\x{042F}0-9 ]+$/ui", $value)) {
			return "Запрещено использование спец. символов!";
		}

		return true;
	}

	function checkTextValidity($value) {
		$value = trim($value);

		if (empty($value)) {
			return "Поле пустое!";
		}
		elseif (strlen($value) < 4) {
			return "Минимум 4 символа!";
		}

		return true;
	}

    $id = 0;
    $title = '';
    $text = '';
    $latest_edit_date = date('Y-m-d');
	$current_edit_date = date('Y-m-d');

	$titleError = "";
	$textError = "";

	$servername = "localhost";
	$username = "lab5";
	$password = "lab5";
	$databasename = "blog";

	$conn = new mysqli($servername, $username, $password, $databasename);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $id = $_GET['id'];

		$sql = "SELECT * FROM posts WHERE id='$id'";
		$result = mysqli_query($conn, $sql);

		$row = mysqli_fetch_assoc($result);

		$latest_edit_date = $row['date'];
		$title = $row['title'];
		$text = $row['text'];

    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$sendedTitle = $_POST['title'];
		$sendedText = $_POST['text'];

		$titleIsValid = checkTitleValidity($sendedTitle);
		$textIsValid = checkTextValidity($sendedText);

		$id = $_GET['id'];

		if (gettype($titleIsValid) == "string") {
			$titleError = $titleIsValid;
		}
		if (gettype($textIsValid) == "string") {
			$textError = $textIsValid;
		}

		$date = $_POST['editDate'];
		$title = $_POST['title'];
		$text = $_POST['text'];


		if ($titleIsValid === true && $textIsValid === true) {
			$sql = "UPDATE posts SET date = '$date', title = '$title', text = '$text' WHERE id='$id'";
			$result = mysqli_query($conn, $sql);

			$conn->close();
			header("Location: http://localhost/lab5/list.php", TRUE, 301);
			exit( );
		}

    }
    ?>

	<div class="wrapper">
		<header class="header">
			<div class="container">
				<nav class="nav">
					<ul class="nav-list">
						<li class="nav-item">
							<a class="nav-link" href="list.php">Главная</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Сообщество</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Пользователи</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Новое</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>
		<main class="main">
			<div class="container">
				<div class="center">
