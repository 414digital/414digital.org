<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
 
/**
 * Stands for MemberMouse Format - formats a currency value into a locale appropriate string
 * i.e. An amount of 100.00 and a currencyCode of USD will yield "$100.00"
 * 
 * @param float $amount The currency value to format
 * @param string $currencyCode The iso code of the currency
 * 
 * @return string The formatted value
 */
 function _mmf($amount, $currencyCode="")
 {
 	$currencyCode = empty($currencyCode)?MM_CurrencyUtil::getActiveCurrency():$currencyCode;
 	return MM_CurrencyUtil::format("%n", $amount, $currencyCode);
 }
 
 
 /**
 * Stands for MemberMouse International Format - formats a currency value into an internationally appropriate string
 * i.e.. An amount of 100.00 and a currencyCode of USD will yield "100.00 USD"
 * 
 * @param float $amount The currency value to format
 * @param string $currencyCode The iso code of the currency
 * 
 * @return The formatted string value
 */
 function _mmif($amount, $currencyCode="")
 {
 	$currencyCode = empty($currencyCode)?MM_CurrencyUtil::getActiveCurrency():$currencyCode;
 	return MM_CurrencyUtil::format("%i", $amount, $currencyCode);
 }
 
 
 /**
  * Stands for MemberMouse Format Currency - returns the 3-character ISO code for the active currency
  * 
  * @return the 3-character iso code for the active currency
  */
 function _mmfc()
 {
 	return MM_CurrencyUtil::getActiveCurrency();
 }
 
 
 /**
  * Stands for MemberMouse Override Format [of Currency] - formats a currency value into a locale appropriate string, using any 
  * supplied settings to override the defaults
  * 
  * @param float $amount The currency value to format
  * @param string $currencyCode (optional) The iso code of the currency
  * @param array $currencySettings (optional) The settings to use when overriding the defaults
  * 
  * @return string The formatted value
  * 
  */
 function _mmof($amount, $currencyCode="",$currencySettings="")
 {
 	$currencyCode = empty($currencyCode)?MM_CurrencyUtil::getActiveCurrency():$currencyCode;
 	return MM_CurrencyUtil::format("%n", $amount, $currencyCode,$currencySettings); 
 }
 
 ?>