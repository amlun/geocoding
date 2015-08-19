<?php
function is_geo($latlng) {
	if (! strpos ( $latlng, ',' )) {
		return false;
	}
	list ( $lat, $lng ) = explode ( ',', $latlng );
	return (isValidLatitude ( $lat ) && isValidLongitude ( $lng ));
}
/**
 * Validates latitude
 *
 * @param mixed $latitude        	
 *
 * @return bool
 */
function isValidLatitude($latitude) {
	return isNumericInBounds ( $latitude, - 90.0, 90.0 );
}

/**
 * Validates longitude
 *
 * @param mixed $longitude        	
 *
 * @return bool
 */
function isValidLongitude($longitude) {
	return isNumericInBounds ( $longitude, - 180.0, 180.0 );
}
/**
 * Checks if the given value is (1) numeric, and (2) between lower
 * and upper bounds (including the bounds values).
 *
 * @param float $value        	
 * @param float $lower        	
 * @param float $upper        	
 *
 * @return bool
 */
function isNumericInBounds($value, $lower, $upper) {
	if (! is_numeric ( $value )) {
		return false;
	}
	if ($value < $lower || $value > $upper) {
		return false;
	}
	return true;
}