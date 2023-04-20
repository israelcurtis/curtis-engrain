# Curtis Engrain Dev Assessment Plugin
 
A simple plugin to demonstrate my skills as your next Senior Backend Developer!
 
 ## Description
 
 This plugin demonstrates the functionality specified in the Engrain Back-end Developer Assessment exercise. It creates a custom post type `unit`, a settings page for importing data, and a shortcode for rendering the results.
 
 ## Installation
 
 1. Upload `curtis-engrain.php` to the `/wp-content/plugins/` directory
 2. Activate the plugin through the 'Plugins' menu in WordPress
 3. Visit the Curtis Engrain settings page to begin the API import
 
 ## WordPress.org Preparation
 
 The original launch of this version of the boilerplate included the folder structure needed for using your plugin on WordPress.org. That folder structure has been moved to its own repo here: https://github.com/DevinVinson/Plugin-Directory-Boilerplate
 
 ## Frequently Asked Questions
 
 * How do I embed a listing of all the units on my page?
 
 After you have successfully imported unit records from the API, you can insert the shortcode `[list-units]` on any page or post, and a two-column listing of all the units and their metadata will appear, grouped by Area.