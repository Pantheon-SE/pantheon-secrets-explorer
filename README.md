Pantheon Secrets Explorer
=========================

A WordPress plugin that integrates with the Pantheon Secrets API, allowing administrators to view secrets and map them to environment variables within WordPress.

Features
--------

-   Secrets Explorer: View all secrets in a tabulated format. The secret values are obscured for security, but can be revealed with a click of a button.

-   Environment Variables Mapper: Map any secret to a WordPress environment variable. The mappings are stored in the `wp_options` table and the environment variables are dynamically created on WordPress bootstrap.

Installation
------------

1.  Install the plugin using Composer:

    ```bash
    composer require pantheon-se/pantheon-secrets-explorer
    ```

1. Activate the plugin in the WordPress admin dashboard.

Usage
-----

1.  Secrets Explorer: In the WordPress admin dashboard, navigate to the "Pantheon Secrets" submenu under "Tools". This will display all secrets from the Pantheon Secrets API.

1. Environment Variables Mapper: In the WordPress admin dashboard, navigate to the "Env Variables" submenu under "Pantheon Secrets". Here, you can define new environment variables and map them to a secret. Existing mappings can be viewed and deleted.

Developer Notes
---------------

-   This plugin relies on the Pantheon Secrets PHP SDK for API interactions. Ensure the SDK is updated regularly for compatibility and security.

Contributions & Support
-----------------------

For contributions, please raise an issue or submit a pull request on our GitHub repository.

For support, reach out to <kyle.taylor@pantheon.io>.

License
-------

This project is licensed under the MIT License.