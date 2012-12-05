<?php
// Shamelessly stolen from http://stuff.nekhbet.ro/2008/12/12/how-to-get-coordinates-for-a-given-address-using-php.html
// (and tweaked slightly for the new v3 API)
	/**
	* Google Geocoding
	* 
	* Copyright (C) 2008 Trimbitas Sorin-Iulian <trimbitassorin@hotmail.com>
	*
	* This program is free software; you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation; either version 2 of the License, or
	* (at your option) any later version.
	*
	* This program is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with this program; if not, write to the Free Software
	* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
	*
	* This class requires cURL and simpleXML to be enabled, also it requires a google maps api key
	* which you can take for free from http://code.google.com/apis/maps/signup.html
	*
	* @class        googleHelper
	* @version      v1.0 09 December 2008
	* @author       Trimbitas Sorin-Iulian <trimbitassorin@hotmail.com>
	* @copyright    2008 Trimbitas Sorin-Iulian
	*/

	/*
	//Test usage : 
	$apiKey = 'ABQIAAAArVQcVxX32bZ7slezKjYHNxRRy_GDkcWXYwd3sTg48YTx-thxPhQCycvjjWX6XIj0M-uyYhSg6sW5QQ';
	$obj = new googleHelper($apiKey);
	print '<pre>';
	print_r($obj->getCoordinates('Romania,Sibiu,Sibiu'));
	*/

	class googleHelper {
		/**
		* Reads an URL to a string
		* @param string $url The URL to read from
		* @return string The URL content
		*/
		private static function getURL($url){
		 	$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$tmp = curl_exec($ch);
			curl_close($ch);
			if ($tmp != false){
			 	return $tmp;
			}
		}
		
		/**
		* Get Latitude/Longitude/Altitude based on an address
		* @param string $address The address for converting into coordinates
		* @return array An array containing Latitude/Longitude/Altitude data
		*/
		public static function getCoordinates($address){
			$address = str_replace(' ','+',$address);
		 	$url = 'http://maps.google.com/maps/geo?q=' . $address . '&output=xml';
		 	$data = $this->getURL($url);
			if ($data){
				$xml = new SimpleXMLElement($data);
				$requestCode = $xml->Response->Status->code;
				if ($requestCode == 200){
				 	//all is ok
				 	$coords = $xml->Response->Placemark->Point->coordinates;
				 	$coords = explode(',',$coords);
				 	if (count($coords) > 1){
				 		if (count($coords) == 3){
						 	return array('lat' => $coords[1], 'long' => $coords[0], 'alt' => $coords[2]);
						} else {
						 	return array('lat' => $coords[1], 'long' => $coords[0], 'alt' => 0);
						}
					}
				}
			}
			//return default data
			return null;
		}
		

	}; //end class
?>
