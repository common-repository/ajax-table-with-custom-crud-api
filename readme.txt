=== AJAX Table with Custom CRUD API ===
Contributors: shajeebs
Tags: bootstrap, filters, data filters, ajax data filters, advanced data filters, CRUD operations, CRUD API, Custom REST API
Tested up to: 5.5
Stable tag: trunk
Requires at least: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==	
'AJAX Table with Custom CRUD API' generates Custom CRUD based REST API for the selected database table enabling user to perform Add/Edit/Delete operations. It also helps to display data from API beautifully using Short Code: [AJDT api='API-NAME'].	
== Features: ==
* Generates CRUD based Custom REST API
* Displays API data using AJAX with shortcode format [AJDT api='API-NAME']
* Integrates Bootsrap table
* Search records with AJAX
* Filter visibility by data category.
* No reloading, only ajax 
== How It Works: ==	
* Install and activate plugin
* Go to Admin area -> AJDT Settings 
* Create API by selecting database table. Test the api by clicking URL field* 
== Usage of API: ==
* Create API by visiting - Go to Admin area -> AJDT Settings
* Get the API Name and API URL
* Test API URL using POSTMAN or any other API testing tool with authentication header 'X-WP-Nonce' with wordpress Rest Nonce
* Use the short code wherever required to display the data
* Syntax
    [AJDT api=' API - Name given while creation from via AJDT Settings']
* Short Code example:-
          [AJDT api='MY-API-NAME']
* From Code example:-      
        echo do_shortcode("[AJDT api='MY-API-NAME']");
== Installation ==
1. Install 'AJAX Table with Custom CRUD API' either via the WordPress.org plugin directory, or by uploading the files to your server
2. Activate 'AJAX Table with Custom CRUD API' and you're ready to go!
== Frequently Asked Questions ==
* Can I use generated REST api in my custom modules? What authentication method should I use? 
Yes, you need to WordPress Cookie Authentication('X-WP-Nonce'). For more info about Cookie Authentication, please refer: https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
* What are the HTTP Methods supported ?
GET, POST and DELETE
* How to render API data  from php code?
Use  echo do_shortcode("[AJDT api='MY-API-NAME']"); Here the MY-API-NAME is the user defined name provided while creating the API.
* My shortcode is not working !
If shortcode content is not seen printed, check the WP settings to see if any option is enabled to restrict where and when shortcode is printed. Also confirm if the shortcode API-NAME is correct and there is no duplicate `api` attribute for the shortcode. Please try the shortcode content in an isolated environment and confirm if the shortcode content is working correctly as expected.
== Screenshots ==
1. AJDT Settings page for managing custom REST APIs.
2. API Create New Page.
3. 'Short Codes' for viewing, and displaying API data 
4. Sample API data 
== Changelog ==
## 1.1.1 
* Initial version