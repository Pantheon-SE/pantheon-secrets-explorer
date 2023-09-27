<?php

namespace PantheonSe\PantheonSecretsExplorer\Admin;

use WP_List_Table;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class EnvVarsTable extends WP_List_Table {

	private array $envVars;

	public function __construct() {
		parent::__construct([
			'singular' => 'Environment Variable',
			'plural'   => 'Environment Variables',
			'ajax'     => false
		]);

		$envVars = get_option('pantheon_secrets_envs', []);
		$data = [];

		foreach ($envVars as $env => $secret) {
			$inline_form = '<form method="POST"><input type="hidden" name="delete_env" value="' . esc_attr($env) . '"><input type="submit" value="Delete" class="button-link-delete"></form>';
			$data[] = ['env' => $env, 'secret' => $secret, 'form' => $inline_form];
		}
		$this->envVars = $data;
	}

	public function get_columns() {
		return [
			'env'    => 'Environment Variable',
			'secret' => 'Assigned Secret',
			'form'   => 'Delete Variable',
		];
	}

	public function prepare_items() {
		$this->_column_headers = [$this->get_columns(), [], []];
		$this->items = $this->envVars;
	}

	public function column_default($item, $column_name) {
		if ($column_name === 'form') {
			return $item[$column_name];
		}
		return esc_html($item[$column_name]);
	}
}
