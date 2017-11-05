# SmartyStreetsAPI
Drupal Module to Integrate smartystreets.com API

This module is being built in order to validate a US address and to also lookup the county of an address.  It uses the smartystreets.com PHP SDK (included in the module).

The reason this is being done is to potentially help make using the tax module within drupal commerce 2.0 more usable in US states that need to take into account the county, city, and state when applying the proper sales tax to a sale.

Currently, under /admin/config/development, there will be 3 menu items relating the SmartyStreetsAPI.

SmartyStreetsAPI Settings
Configure SmartyStreetsAPI Settings
-in this area, you need to already have an account setup on smartystreets.com and have the API auth ID and auth token.
-the "Log all SmartyStreets API calls" and "Log all SmartyStreets API responses" does nothing presently.

SmartyStreetsAPI Testing Form
Test SmartyStreetsAPI Form
-this is a form that allows for street address, city, and state to be entered and submitted.
-when submitted, this will lookup the address entered on smartystreets.com and return information about it (including county)

SmartyStreetsAPI Testing
Test SmartyStreetsAPI
-This currently validates and looks up info on a hard-coded address.  This will be removed shortly.
