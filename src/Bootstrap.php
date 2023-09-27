<?php

namespace PantheonSe\PantheonSecretsExplorer;

use PantheonSe\PantheonSecretsExplorer\Admin\AdminPage;
use PantheonSystems\CustomerSecrets\CustomerSecrets;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Bootstrap {

	public static function initialize() {
		// Register our main admin page
		$explorer = new AdminPage();
		$explorer->init();

		// Load secrets early to define constants
		add_action('plugins_loaded', [self::class, 'loadSecrets']);
	}

	public static function loadSecrets() {
		$options = get_option('pantheon_secrets_envs', []);
		$client = CustomerSecrets::create()->getClient();

		foreach ($options as $envName => $secretName) {
			$secretValue = $client->getSecret($secretName)->getValue();
			if (!defined($envName)) {
				define($envName, $secretValue);
			}
		}
	}
}
