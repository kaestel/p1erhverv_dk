<?php
$IC = new Items();

$page_item = $IC->getItem(array("tags" => "page:elstedhøj", "status" => 1, "extend" => array("mediae" => true, "tags" => true)));
if($page_item) {
	$this->sharingMetaData($page_item);
}

?>
<div class="scene monteringsplan i:scene">

<? if($page_item): 
	$media = $IC->sliceMediae($page_item); ?>
	<div class="article i:article" itemscope itemtype="http://schema.org/Article">

		<? if($media): ?>
		<div class="image item_id:<?= $page_item["item_id"] ?> format:<?= $media["format"] ?> variant:<?= $media["variant"] ?>"></div>
		<? endif; ?>


		<?= $HTML->articleTags($page_item, [
			"context" => false
		]) ?>


		<h1 itemprop="headline"><?= $page_item["name"] ?></h1>

		<? if($page_item["subheader"]): ?>
		<h2 itemprop="alternativeHeadline"><?= $page_item["subheader"] ?></h2>
		<? endif; ?>


		<?= $HTML->articleInfo($page_item, "/elstedhøj", [
			"media" => $media, 
			"sharing" => true
		]) ?>


		<? if($page_item["html"]): ?>
		<div class="articlebody" itemprop="articleBody">
			<?= $page_item["html"] ?>
		</div>
		<? endif; ?>

	</div>
<? endif; ?>

</div>