<?php

	// set up beer class
	class Beer {
		// properties
		public $id;
		public $name;
		public $price;
		public $inStock;
		public $family;
		public $isActive;

		// constructor
		function __construct($id, $name, $family, $price, $quantity, $active = true) {
			$this->id = $id;
			$this->name = $name;
			$this->price = $price;
			$this->inStock = $quantity;
			$this->family = $family;
			$this->isActive = $active;
		}

		// other methods
		public function isQuantityInStock($quan) {
			if ($this->inStock < $quan) {
				echo '<div class="error">Sorry, we only have ' .
					$this->inStock . ' of ' . $this->name . ' in stock.</div>';
				return false;
			}
			return true;
		}
	}

	class Family {
		public $id;
		public $name;

		function __construct($id, $name) {
			$this->id = $id;
			$this->name = $name;
		}
	}
?>