<?php

namespace PantheonSe\PantheonSecretsExplorer\Admin;

use WP_List_Table;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class SecretsListTable extends WP_List_Table {
	private array $secrets_data;

	public function __construct($secrets) {
		parent::__construct([
			'singular' => 'secret',
			'plural'   => 'secrets',
			'ajax'     => false
		]);

		$this->secrets_data = $secrets;
	}

	public function get_columns(): array {
		return [
			'name'  => 'Secret Name',
			'value' => 'Secret Value'
		];
	}

	public function prepare_items() {
		$this->_column_headers = [$this->get_columns(), [], []];
		$this->items = $this->secrets_data;
	}

	public function column_default($item, $column_name) {
		switch ($column_name) {
			case 'name':
				return esc_html($item->getName());

			case 'value':
				$value = $item->getValue();
				return '<input type="password" readonly value="' . esc_attr($value) . '" class="pse-secret-value">
                        <button class="button button-secondary pse-toggle-value">Show</button>';

			default:
				return print_r($item, true);
		}
	}
}
