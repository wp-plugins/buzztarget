=== BuzzTarget ===
Contributors: buzztarget
Donate link: http://www.buzztarget.com
Tags: real estate listings, commercial real estate listings, commercial listing marketing, CRE
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 3.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Commercial Real Estate Listing integration with BuzzTarget.  Publish your BuzzTarget listings to your Private WordPress powered site.

== Description ==

BuzzTarget is a 3-in-1 platform for commercial real estate professionals that allows free unlimited listings, 
targeted email marketing distribution to our community of industry professionals, and the ability to target 
and manage your own private contacts lists.

BuzzTarget is perfect for commercial real estate professionals who want to increase and improve their marketing efforts; reach a targeted audience; 
announce or receive new listings information; call for or receive offers on listings; search for investment opportunities; 
find financing or locate space; get deals done.

The BuzzTarget plugin allows a broker to retrieve his/her listings from BuzzTarget and display it on his/her  WordPress powered site.  The plugin eliminates 
duplicate entry and unnecessary maintenance of listings on multiple sites.  With this responsive plugin, listings are pulled on a scheduled basis (or on demand).  Listing and 
Listing Detail pages, Featured Listings and Broker Listings can be created by utilizing simple short codes and widgets.

To see an example of a website using the plugin, please visit: http://credemo.azurewebsites.net/
 
== Installation ==

Here is how to get this plugin installed and get it working

1. Upload the buzztarget plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Obtain your API Key and Secret Keys from BuzzTarget administrator (support@buzztarget.com)
4. Enter the provided keys on the BuzzTarget -> API Settings page
5. Determine Listing to fetch and at what intervals on the BuzzTarget -> Listings page

To display your BuzzTarget listings on your site perform the following:

1. Fetch all of your listings.  Click Fetch on the BuzzTarget -> Listings page
2. Confirm that all of the listings were pulled by viewing the Properties tab
3. Add the required pages and place the respective short code to view the listings:
	a. For Sale - [for-sale][/for-sale]
	b. For Lease - [for-lease][/for-lease]
	c. All Listings - [all-listings][/all-listings]
4. Broker Listings Short Code Example:
	[all-broker-listings title="My Listings"  class="broker-listing-title" numberOfListingPerRow="9" numberOfListingPerRow="4" brokerEmail="broker@domain.com"][/all-broker-listings]
5. Featured Listings Short Code Example:
	[all-featured numberOfListingPerRow="3"][/all-featured]

== Frequently Asked Questions ==

= Why are my listings not fetched? =

Please ensure that your API keys are correct and entered into the correct text boxes.

= Who do I reach out to in case I have additional questions? =

please send your inquires to support@buzztarget.com

= Do you have any sample websites that use your pugin? =

Yes, we do.  http://credemo.azurewebsites.net/


= Will the plugin work with my Theme? =

Our plugin works with many Themes.  Using the CSS editor, you can customize fonts and colors to match your theme.  Please send us an email if you have any custom requirements.



== Screenshots ==

1. Properties Page in List View.
2. Property Detail Page in One Column View.
3. Broker Page with Broker Listings Widget.
4. Featured Listings Widget.
5. Contact Registration Widget.
6. WP-Admin Listings Configuration Page.
7. WP-Admin Listings Fetch Page.
8. Responsive / Mobile View.

== Changelog ==

= 3.0.0 =
* Added: 
  - Customizable pins on the Map
  - New Listings Map widgets
  - Introduced Attachment Icons
  - Introduced ability to search by Acres

= 2.0.7 =
* Enhanced Short Code generator to allow for filtering listings by property type and listing type

= 2.0.6 =
* Style changes

= 2.0.5 =
* Tested with WordPress 4.0

= 2.0.4 =
* Added enhancement for Undisclosed Pricing

= 2.0.3 =
* Minor Style Changes
* Bug Fixes

= 2.0.2 =
* Improved Searching
* Bug Fixes

= 2.0.1 =
* Added ability to sort by Property Name
* Option to hide property price from the listings page
* Fixed performance bugs

= 2.0.0 =
* Enhanced Listings View Page - Ability to select from two different View Styles
* Enhanced Listings Detail Page - Ability to select from two different View Styles
* Added Featured Listings Widget
* Added Broker Listings Widget
* Enabled Responsive compatibility to be able to view on tablets and mobile devices
* Added CSS Editor to Listings Settings in WP-Admin panel
* Improved Listings Fetch Mechanism

= 1.0.4 =
* Added Paging to Broker Listings

= 1.0.3 =
* Fixed issues with Listing Detail and Listing View Pages

= 1.0.2 =
* Fixed Listing Fetch mechanism and minor issues with property listing pages.

= 1.0.1 =
* Bug Fixes

= 1.0.0 =
* Initial version

== Upgrade Notice ==

= 1.0.0 =
This is the initial version