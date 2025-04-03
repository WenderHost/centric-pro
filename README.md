# Case Antiques Centric Pro Theme #
**Contributors:** [thewebist](https://profiles.wordpress.org/thewebist/)  
**Tags:** genesis, centricpro  
**Requires at least:** 6.5  
**Tested up to:** 6.7.2  
**Requires PHP:** 8.1  
**Stable tag:** 1.4.0  
**License:** GPLv2 or later  
**License URL:** https://www.gnu.org/licenses/gpl-2.0.html  

A customized version of the Centric Pro theme for [caseantiques.com](https://caseantiques.com).

## Description ##

This is a modified version of the Genesis child theme Centric Pro for using on the Case Antiques website.

## Changelog ##

### 1.4.0 ###
* Handling display of `LotBiddingURL` in `single-item.php`.

### 1.3.4 ###
* Replacing call to `sprintf` with `str_replace` in `centric_auction_table()` due to a parsing error.

### 1.3.3 ###
* Adding compiled CSS to repo (i.e. `lib/css/`).

### 1.3.2 ###
* Checking for `display_bid_now_button` in `single-item.php`.
* Adding "Display 'Bid Now' Button" option to Auction taxonomies.
* Adding check for `_item_number` to `single-item.php` in prepartion for utilizing Sirv image hosting.

### 1.3.1 ###
* Removing "More Info" link for single items.

### 1.3.0 ###
* Adding ACF Auction Taxonomy fields.
* Updating `single-item.php` to reference ACF Auction Taxonomy fields.

### 1.2.0 ###
* Updating gallery images to work with [Featherlight](https://github.com/noelboss/featherlight).

### 1.1.3 ###
* Refactoring responsive images filter to not use deprecated `create_function()`.
* Allowing multiple ACF JSON load points.
* Removing Gravity Forms auth-only filter in favor of another plugin.

### 1.1.2 ###
* Updating `single-item.php` template to support Live Auctioneer IDs supplied via the Item's meta data.

### 1.1.1 ###
* Add theme setting defaults

### 1.1.0 ###
* Update theme setting defaults
* Use theme supports for after entry widget
* HTML5 Galleries
* Fix responsive header logo