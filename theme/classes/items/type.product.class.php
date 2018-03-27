<?php
/**
* @package janitor.items
* This file contains item type functionality
*/

class TypeProduct extends Itemtype {

	/**
	* Init, set varnames, validation rules
	*/
	function __construct() {

		// construct ItemType before adding to model
		parent::__construct(get_class());

		// itemtype database
		$this->db = SITE_DB.".item_product";

		// Name
		$this->addToModel("name", array(
			"type" => "string",
			"label" => "Name",
			"required" => true,
			"unique" => $this->db,
			"hint_message" => "Name of the product - Format: Miele - AX11.", 
			"error_message" => "Name must to be unique."
		));

		// Description
		$this->addToModel("description", array(
			"type" => "text",
			"label" => "Description",
			"hint_message" => "Write a meaningful description of the product. Remember product descriptions are very important for Google - Make sure to use varied language and include all relevant keywords in your description."
		));

		// HTML
		$this->addToModel("html", array(
			"allowed_tags" => "p,h3,h4,download",
			"required" => true,
		));

		// EAN
		$this->addToModel("ean", array(
			"type" => "string",
			"label" => "EAN Number",
			"hint_message" => "European Article Number - 13 digit product identification number"
		));

		// Price
		$this->addToModel("price", array(
			"type" => "string",
			"label" => "Price (only shown if selected for client)",
			"hint_message" => "State the price incl. currency – like: DKK 2.345,-."
		));


	}

}

?>
