# SmartyStreetsAPI
Drupal Module to Integrate smartystreets.com API

This module is being built in order to validate a US address and to also lookup the county of an address.  It uses the smartystreets.com PHP SDK (included in the module).

The reason this is being done is to potentially help make using the tax module within drupal commerce 2.0 more usable in US states that need to take into account the county, city, and state when applying the proper sales tax to a sale.

Currently, under /admin/config/development, there will be 1 menu item relating the SmartyStreetsAPI.

SmartyStreetsAPI Settings
Configure SmartyStreetsAPI Settings
-in this area, you need to already have an account setup on smartystreets.com and have the API auth ID and auth token.
-the "Log all SmartyStreets API calls" and "Log all SmartyStreets API responses" does nothing presently.

There is a test module called ValidateAddress that can be used to test this service.  Currently, the ValidateAddress module can be found here: https://github.com/labwebdev/ValidateAddress
