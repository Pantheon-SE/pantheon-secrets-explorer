<?php

namespace PantheonSe\PantheonSecretsExplorer\Admin;

use PantheonSystems\CustomerSecrets\CustomerSecrets;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class AdminPage {

	/**
	 * Initialize the admin page.
	 * @return void
	 */
	public function init() {
		add_action('admin_menu', [$this, 'registerAdminPages']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
		add_action('admin_init', [self::class, 'registerSettings']);
	}

	/**
	 * Add the admin menu.
	 * @return void
	 */
	public static function registerAdminPages() {
		add_menu_page(
			'Pantheon Secrets Explorer',
			'Pantheon Secrets',
			'manage_options',
			'pantheon-secrets-explorer',
			[self::class, 'displaySecretsPage'],
			'dashicons-lock', // For example, using the lock icon
			100
		);

		add_submenu_page(
			'pantheon-secrets-explorer',
			'Environment Variables',
			'Env Variables',
			'manage_options',
			'pantheon-env-variables',
			[self::class, 'displayEnvVariablesPage']
		);
	}


	/**
	 * Register settings.
	 * @return void
	 */
	public static function registerSettings() {
		register_setting('pantheon_secrets_options_group', 'pantheon_secrets_envs', 'sanitize_env_options');
	}

	/**
	 * Display environment variables page.
	 * @return void
	 */
	public static function displayEnvVariablesPage() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$envVars = (array) get_option('pantheon_secrets_envs', []);
			if (isset($_POST['env_name']) && isset($_POST['secret_name'])) {
				$envVars[trim($_POST['env_name'])] = trim($_POST['secret_name']);
			}
			if (isset($_POST['delete_env'])) {
				unset($envVars[$_POST['delete_env']]);
			}
			update_option('pantheon_secrets_envs', $envVars);
		}

		$client = CustomerSecrets::create()->getClient();
		$secrets = $client->getSecrets();

		echo '<div class="wrap">';

		// Form for adding new environment variables
		echo '<h2>Add New Environment Variable</h2>';
		echo '<p>Use this form to add a new environment variable and assign it a secret.</p>';

		echo '<form method="POST">';
		// Use settings API
		settings_fields('pantheon_secrets_options_group');
		do_settings_sections('pantheon-secrets-explorer');

		echo '<table class="form-table">';
		echo '<tr>';
		echo '<th><label for="env_name">Environment Variable Name</label></th>';
		echo '<td><input type="text" name="env_name" id="env_name" class="regular-text"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th><label for="secret_name">Select Secret</label></th>';
		echo '<td><select name="secret_name" id="secret_name">';

		foreach ($secrets as $secret) {
			$key = $secret->getName();
			echo '<option value="' . esc_attr($key) . '">' . esc_html($key) . '</option>';
		}

		echo '</select></td>';
		echo '</tr>';
		echo '</table>';

		submit_button('Add Environment Variable');
		echo '</form>';
		echo '<hr>';

		// Display existing environment variables and their assigned secrets
		$envVarsTable = new EnvVarsTable();
		$envVarsTable->prepare_items();
		if (!empty($envVarsTable->items)) {
			echo '<h2>Environment Variables Assignments</h2>';
			echo '<p>Use this table to view and delete existing environment variable assignments.</p>';
			$envVarsTable->display();
			echo '<br>';
		}

		echo '</div>';
	}

	/**
	 * Display secret settings.
	 * @return void
	 */
	public static function displaySecretsPage() {

		$client = CustomerSecrets::create()->getClient();
		$secrets = $client->getSecrets();

		echo '<div class="wrap">';
		echo '<h1>Pantheon Secrets Explorer</h1>';
		echo '<p>Pantheon’s Secrets Manager Terminus plugin is key to maintaining industry best practices for secure builds and application implementation. Secrets Manager provides a convenient mechanism for you to manage your secrets and API keys directly on the Pantheon platform.</p>';
		echo '<p>For more information, see <a href="https://github.com/pantheon-systems/terminus-secrets-manager-plugin">https://github.com/pantheon-systems/terminus-secrets-manager-plugin</a>.</p>';

		// Instantiate the table class
		$list_table = new SecretsListTable($secrets);
		$list_table->prepare_items();
		$list_table->display();

		echo '</div>';
	}

	public function enqueueAdminScripts($hook) {
		if ('settings_page_pantheon-secrets-explorer' !== $hook) {
			return;
		}

		wp_enqueue_style(
			'pantheon-secrets-admin-css',
			plugin_dir_url(__FILE__) . 'assets/admin.css',
			[],
			'1.0.0'
		);

		wp_enqueue_script(
			'pantheon-secrets-admin-js',
			plugin_dir_url(__FILE__) . 'assets/admin.js',
			['jquery'],
			'1.0.0',
			true
		);
	}
}
