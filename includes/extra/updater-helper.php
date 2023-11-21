<?php

if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
        'proper_folder_name' => 'taxonomy-search', // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/SirajumMahdi/taxonomy-search', // the GitHub API url of your GitHub repo
        'raw_url' => 'https://raw.githubusercontent.com/SirajumMahdi/taxonomy-search/main/', // the GitHub raw url of your GitHub repo
        'github_url' => 'https://github.com/SirajumMahdi/taxonomy-search.git', // the GitHub url of your GitHub repo
        'zip_url' => 'https://github.com/SirajumMahdi/taxonomy-search/archive/refs/heads/main.zip', // the zip url of the GitHub repo
        'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
        'requires' => '6.3', // which version of WordPress does your plugin require?
        'tested' => '6.4.1', // which version of WordPress is your plugin tested up to?
        'readme' => 'README.md', // which file to use as the readme for the version number
        'access_token' => '', // Access private repositories by authorizing under Plugins > GitHub Updates when this example plugin is installed
    );
    new WP_GitHub_Updater($config);
}