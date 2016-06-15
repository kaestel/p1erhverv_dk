<?php
/**
* @package janitor.itemtypes
* This file contains itemtype functionality
*/

class TypeClient extends Itemtype {

	/**
	* Init, set varnames, validation rules
	*/
	function __construct() {

		// construct ItemType before adding to model
		parent::__construct(get_class());

		// itemtype database
		$this->db = SITE_DB.".item_client";

		$this->db_users = SITE_DB.".item_client_users";
		$this->db_products = SITE_DB.".item_client_products";


		// Name
		$this->addToModel("name", array(
			"type" => "string",
			"label" => "Name",
			"required" => true,
			"hint_message" => "Name your client", 
			"error_message" => "Name must be filled out"
		));

		// HTML
		$this->addToModel("html", array(
			"required" => true,
		));

		// secret_url_token
		$this->addToModel("secret_url_token", array(
			"type" => "string",
			"label" => "Secret URL token",
			"required" => true,
			"hint_message" => "An identifier that allows the client to view the client pages without logging in",
			"error_message" => "Token must be filled out"
		));

	}


	// get clients available for current user
	function getClients() {

		$IC = new Items();
		$user_id = session()->value("user_id");


		$query = new Query();
		$query->checkDbExistance($this->db_users);


		$clients = array();
		$sql = "SELECT items.id, items.itemtype, items.sindex, items.published_at, items.modified_at, items.user_id FROM ".UT_ITEMS." as items, ".$this->db_users." as users WHERE users.client_id = items.id AND users.user_id = $user_id AND items.status = 1";
//		print $sql;
		if($query->sql($sql)) {
			$clients = $query->results();
			$clients = $IC->extendItems($clients, array("user" => true));
		}

		return $clients;


		// $clients = $IC->getItems(array("itemtype" => "client", "status" => 1, "extend" => array("user" => true)));
		// return $clients;

	}


	// get clients available for client id
	function getProducts($client_id) {

		$IC = new Items();
		$query = new Query();
		$query->checkDbExistance($this->db_products);


		$products = array();
		$sql = "SELECT items.id, items.itemtype, items.sindex, items.published_at, items.modified_at, items.user_id FROM ".UT_ITEMS." as items, ".$this->db_products." as products WHERE products.product_id = items.id AND products.client_id = $client_id ORDER BY products.position ASC";
//		print $sql;
		if($query->sql($sql)) {
			$products = $query->results();
			$products = $IC->extendItems($products, array("tags" => true, "mediae" => true, "user" => true));
		}

		return $products;

	}

	// add product to client
	function addProduct($action) {

		if(count($action) == 3) {
			$client_id = $action[1];
			$product_id = $action[2];

			$query = new Query();
			$query->checkDbExistance($this->db_products);


			$sql = "INSERT INTO ".$this->db_products." VALUES(DEFAULT, ".$client_id.", ".$product_id.", 0)";
			// print $sql;
			if($query->sql($sql)) {
				message()->addMessage("Product added");
				return true;				
			}

		}

		message()->addMessage("Product could not be added", array("type" => "error"));
		return false;
	}

	// remove product from client
	function removeProduct($action) {

		if(count($action) == 3) {
			$client_id = $action[1];
			$product_id = $action[2];

			$query = new Query();
			$query->checkDbExistance($this->db_products);


			$sql = "DELETE FROM ".$this->db_products." WHERE client_id = $client_id AND product_id = $product_id";
			// print $sql;
			if($query->sql($sql)) {
				message()->addMessage("Product removed");
				return true;
			}

		}

		message()->addMessage("Product could not be removed", array("type" => "error"));
		return false;
	}

	// Update product order
	function updateProductOrder($action) {

		$order_list = getPost("order");
		if(count($action) == 2 && $order_list) {

			$client_id = $action[1];
			$query = new Query();
			$order = explode(",", $order_list);

			for($i = 0; $i < count($order); $i++) {
				$item_id = $order[$i];
				$sql = "UPDATE ".$this->db_products." SET position = ".($i+1)." WHERE product_id = ".$item_id." AND client_id = $client_id";
				$query->sql($sql);
			}

			message()->addMessage("Order updated");
			return true;
		}
		
		message()->addMessage("Product order could not be updated", array("type" => "error"));
		return false;

	}



	// get user list for this client
	function getUsers($client_id) {

		$query = new Query();
		$query->checkDbExistance($this->db_users);

		// get all users
		$all_users = array();
		if($query->sql("SELECT users.nickname, users.id FROM ".SITE_DB.".users as users WHERE users.user_group_id > 1 ORDER BY users.nickname")) {
			$all_users = $query->results();
		}

		// get users for this client
		$client_users = array();
		if($query->sql("SELECT user_id FROM ".$this->db_users." WHERE client_id = $client_id")) {
			$client_users = $query->results();
		}

		// make complete list
		foreach($all_users as $index => $user) {
			if(arrayKeyValue($client_users, "user_id", $user["id"]) !== false) {
				$all_users[$index]["selected"] = true;
			}
			else {
				$all_users[$index]["selected"] = false;
			}
		}
		
		return $all_users;
	}

	// add user access to this client
	function addUser($action) {

		if(count($action) == 3) {
			$client_id = $action[1];
			$user_id = $action[2];

			$query = new Query();
			$query->checkDbExistance($this->db_users);


			$sql = "INSERT INTO ".$this->db_users." VALUES(DEFAULT, ".$client_id.", ".$user_id.")";
			// print $sql;
			if($query->sql($sql)) {
				message()->addMessage("User added");
				return true;
			}

		}

		message()->addMessage("User could not be added", array("type" => "error"));
		return false;
	}

	// add user access to this client
	function removeUser($action) {

		if(count($action) == 3) {
			$client_id = $action[1];
			$user_id = $action[2];

			$query = new Query();
			$query->checkDbExistance($this->db_users);


			$sql = "DELETE FROM ".$this->db_users." WHERE client_id = $client_id AND user_id = $user_id";
			// print $sql;
			if($query->sql($sql)) {
				message()->addMessage("User removed");
				return true;
			}

		}

		message()->addMessage("User could not be removed", array("type" => "error"));
		return false;
	}


}

?>