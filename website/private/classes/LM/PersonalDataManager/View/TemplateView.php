<?php

namespace LM\PersonalDataManager\View;

use LM\WebFramework\View\IView;

abstract class TemplateView implements IView
{
	public function displayAllBreadcrumbLis(array $breadcrumb): void
	{
		foreach ($breadcrumb as $name => $link) {
			$this->displaySingleBreadcrumbLi($name, $link);
		}
	}

	public function displaySingleBreadcrumbLi(string $name, string $link): void
	{
?>
<li>
	<a href="<?= $link ?>">
		<?= $name ?>
	</a>
</li>
<?php
	}
	public function displayHtmlHeadContent(string $pageTitle): void
	{
?>
<meta charset="utf-8">
<title><?= $pageTitle ?></title>
<link href="style.css" rel="stylesheet" title="Default Style" type="text/css">
<?php
	}

	public function displayPageHeader(string $pageHeader, array $breadcrumb): void
	{
?>
	<header>
		<h1 class="no-margin-bottom">
			<div class="black-background">
				<?= $pageHeader ?>
			</div>
		</h1>
		<div class="main-black-transparent-background">
			<nav>
				<ol class="breadcrumb">
					<?php $this->displayAllBreadcrumbLis($breadcrumb) ?>
				</ol>
			</nav>
		</div>
	</header>
<?php
	}
}