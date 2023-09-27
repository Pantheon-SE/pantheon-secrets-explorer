<?php

/**
 * Plugin Name: Pantheon Secrets Explorer
 * Description: Explore and copy Pantheon secrets.
 * Author: Kyle Taylor
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once __DIR__ . '/vendor/autoload.php';


use PantheonSe\PantheonSecretsExplorer\Bootstrap;

// Bootstrap values
Bootstrap::initialize();
