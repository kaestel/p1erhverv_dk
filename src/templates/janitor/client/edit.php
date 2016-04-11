<?php
global $action;
global $IC;
global $model;
global $itemtype;

$item_id = $action[1];
$item = $IC->getItem(array("id" => $item_id, "extend" => true));

$active_items = $model->getProducts($item_id);
$passive_items = $IC->getItems(array("itemtype" => "product", "status" => 1, "extend" => true));

$users = $model->getUsers($item_id);
?>
<div class="scene defaultEdit <?= $itemtype ?>Edit">
	<h1>Edit client</h1>
	<h2><?= $item["name"] ?></h2>

	<?= $JML->editGlobalActions($item) ?>

	<div class="item i:defaultEdit i:collapseHeader">
		<h2>Client details</h2>
		<?= $model->formStart("update/".$item["id"], array("class" => "labelstyle:inject")) ?>

			<fieldset>
				<?= $model->input("name", array("value" => $item["name"])) ?>
				<?= $model->inputHTML("html", array("value" => $item["html"])) ?>
			</fieldset>

			<?= $JML->editActions($item) ?>

		<?= $model->formEnd() ?>
	</div>

	<div class="products i:collapseHeader">
		<h2>Products</h2>
		<p>Select which products should be visible for this client.</p>
		<h3>Active products</h3>
		<div class="all_items active filters sortable i:defaultList i:activeProducts"
		 	data-csrf-token="<?= session()->value("csrf") ?>" 
			data-item-order="<?= $this->validPath("/janitor/client/updateProductOrder/".$item["id"]) ?>"
			data-item-remove="<?= $this->validPath("/janitor/client/removeProduct/".$item["id"]) ?>"
		 	>
			<ul class="items">
			<? foreach($active_items as $a_item): ?>
				<li class="item product item_id:<?= $a_item["id"] ?>">
					<h3><?= $a_item["name"] ?></h3>
				</li>
			<? endforeach; ?>
			</ul>
		</div>
		<h3>Passive products</h3>
		<div class="all_items inactive filters i:defaultList i:inactiveProducts"
		 	data-csrf-token="<?= session()->value("csrf") ?>" 
			data-item-add="<?= $this->validPath("/janitor/client/addProduct/".$item["id"]) ?>"
		 	>
			<ul class="items">
			<? foreach($passive_items as $p_item): ?>
				<li class="item product item_id:<?= $p_item["id"] ?><?= (arrayKeyValue($active_items, "id", $p_item["id"]) !== false) ? " active" : "" ?>">
					<h3><?= $p_item["name"] ?></h3>
				</li>
			<? endforeach; ?>
			</ul>
		</div>
	</div>

	<div class="users i:collapseHeader i:clientUsers"
 		data-csrf-token="<?= session()->value("csrf") ?>" 
		data-item-remove="<?= $this->validPath("/janitor/client/removeUser/".$item["id"]) ?>"
		data-item-add="<?= $this->validPath("/janitor/client/addUser/".$item["id"]) ?>"
		>
		<h2>Users</h2>

		<ul class="users">
		<? foreach($users as $user): ?>
			<li class="user user_id:<?= $user["id"] ?><?= $user["selected"] ? " selected" : "" ?>"><?= $user["nickname"] ?></li>
		<? endforeach; ?>
		</ul>
	</div>

</div>
