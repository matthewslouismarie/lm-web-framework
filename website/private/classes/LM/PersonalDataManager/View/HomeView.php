<?php

namespace LM\PersonalDataManager\View;

use LM\WebFramework\View\IView;

class HomeView implements IView
{
	public function display(): void
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home</title>
	</head>
	<body>
		<header>
			<h1>Home</h1>
		</header>
		<main>
			<p>Personal Data Manager is the essential tool to help you manage your life! <a href="index.php?page=login">Log in!</a></p>
		</main>
	</body>
</html>
<?php
	}
}