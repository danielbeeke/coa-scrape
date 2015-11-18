<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$locations = ["/nl/opvanglocaties/alkmaar", "/nl/opvanglocaties/almelo", "/nl/opvanglocaties/almere", "/nl/opvanglocaties/alphen-aan-den-rijn", "/nl/opvanglocaties/amersfoort", "/nl/opvanglocaties/apeldoorn", "/nl/opvanglocaties/arnhem-de-berg", "/nl/opvanglocaties/arnhem-zuid-vreedenburgh-0", "/nl/opvanglocaties/azelo", "/nl/opvanglocaties/baexem-1", "/nl/opvanglocaties/bellingwolde", "/nl/bergen-aan-zee", "/nl/opvanglocaties/breda", "/nl/opvanglocaties/budel-cranendonck", "/nl/opvanglocaties/budel-dorplein", "/nl/opvanglocaties/burgum", "/nl/opvanglocaties/delfzijl-1", "/nl/opvanglocaties/den-haag", "/nl/opvanglocaties/den-haag-loosduinen", "/nl/opvanglocaties/den-helder-doggershoek", "/nl/opvanglocaties/den-helder-maaskampkazerne", "/nl/opvanglocaties/den-helder-gezinslocatie", "/nl/opvanglocaties/deventer", "/nl/opvanglocaties/doetinchem", "/nl/opvanglocaties/drachten", "/nl/opvanglocaties/dronten", "/nl/opvanglocaties/echt", "/nl/opvanglocaties/ede", "/nl/opvanglocaties/eindhoven", "/nl/opvanglocaties/emmen", "/nl/opvanglocaties/geeuwenbrug", "/nl/content/azc-gilze-en-rijen", "/nl/opvanglocaties/goes", "/nl/opvanglocaties/grave", "/nl/opvanglocaties/groningen", "/nl/opvanglocaties/gulpen-wittem", "/nl/opvanglocaties/haarlem", "/nl/opvanglocaties/heerhugowaard", "/nl/opvanglocaties/heerlen", "/nl/opvanglocaties/hengelo-ov-avo", "/nl/opvanglocaties/hoogeveen", "/nl/opvanglocaties/katwijk", "/nl/opvanglocaties/kolham", "/nl/opvanglocaties/leersum-0", "/nl/opvanglocaties/leeuwarden", "/nl/opvanglocaties/leiden", "/nl/opvanglocaties/luttelgeest", "/nl/opvanglocaties/maastricht", "/nl/opvanglocaties/middelburg", "/nl/opvanglocaties/musselkanaal", "/nl/opvanglocaties/nijmegen-heumensoord", "/nl/opvanglocaties/nijmegen", "/nl/opvanglocaties/oisterwijk", "/nl/opvanglocaties/ommen", "/nl/opvanglocaties/onnen", "/nl/opvanglocaties/ootmarsum", "/nl/opvanglocaties/oranje", "/nl/opvanglocaties/oude-pekela", "/nl/opvanglocaties/overberg", "/nl/opvanglocaties/overloon", "/nl/rosmalen", "/nl/opvanglocaties/rijswijk-centraal-bureau", "/nl/opvanglocaties/schalkhaar", "/nl/opvanglocaties/scheerwolde", "/nl/opvanglocaties/sint-annaparochie", "/nl/opvanglocaties/stadskanaal", "/nl/opvanglocaties/sweikhuizen", "/nl/ter-apel-col-0", "/nl/opvanglocaties/tilburg", "/nl/opvanglocaties/utrecht", "/nl/opvanglocaties/veenhuizen", "/nl/opvanglocaties/velp", "/nl/opvanglocaties/venlo", "/nl/opvanglocaties/vierhouten", "/nl/opvanglocaties/vledder", "/nl/opvanglocaties/wageningen", "/nl/opvanglocaties/wassenaar", "/nl/opvanglocaties/weert", "/nl/opvanglocaties/winterswijk-0", "/nl/opvanglocaties/zaanstad", "/nl/opvanglocaties/zaanstad-noodopvang", "/nl/opvanglocaties/zeist", "/nl/opvanglocaties/zweeloo-1", "/nl/opvanglocaties/zwolle-ijsselhallen"];

ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');

$result = [];

foreach ($locations as $location) {
	$hash = md5($location);
	$filename = 'cache/' . $hash . '.html';
	$location_html = '';

	if (!file_exists($filename)) {
		$location_html = file_get_contents('https://www.coa.nl' . $location);
		if ($location_html) {
			file_put_contents($filename, $location_html);
		}
	}

	if (!$location_html) {
		$location_html = file_get_contents('./' . $filename);
	}

	$dom = new DomDocument;
	$dom->loadHTML($location_html);

	$xpath = new DOMXPath($dom);

	$terzijde = $xpath->query('//div[contains(@class,"views-field-field-terzijde")]')->item(0);

	if ($terzijde) {
		$matches = [];
		$regex = '/((?:[0-9]+,)*[0-9]+(?:\.[0-9]+)?)/';
		preg_match_all($regex, $terzijde->nodeValue, $matches);

		$count = intval(str_replace('.', '', $matches[0][0]));
	}

	$street_block = $xpath->query('//div[contains(@class,"thoroughfare")]')->item(0);
	$locality_block = $xpath->query('//div[contains(@class,"locality-block")]')->item(0);

	if ($street_block && $locality_block) {
		$address = $street_block->nodeValue . " " . $locality_block->nodeValue . ' NL';
		$google_geocoder_url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=';

		$geom_filename = 'cache/' . $hash . '.geom';

		$geocoded = '';

		if (!file_exists($geom_filename)) {
			$geocoded = file_get_contents($google_geocoder_url . urlencode($address));

			if ($geocoded) {
				file_put_contents($geom_filename, $geocoded);
			}
		}

		if (!$geocoded) {
			$geocoded = file_get_contents($geom_filename);
		}

		$geo_json = json_decode($geocoded, TRUE);

		$formatted_address = $geo_json['results'][0]['formatted_address'];

		$lat = $geo_json['results'][0]['geometry']['location']['lat'];
		$lng = $geo_json['results'][0]['geometry']['location']['lng'];

		$address_components_massaged = [];

		foreach ($geo_json['results'][0]['address_components'] as $address_component) {
			foreach ($address_component['types'] as $type) {
				$address_components_massaged[$type] = $address_component;
			}
		}

		$city = $address_components_massaged['locality']['long_name'];
	}

	$result[] = array(
		'type' => 'Feature',
		'geometry' => [
			'type' => 'Point',
			'coordinates' => [$lng, $lat]
		],
		'properties' => [
			'city' => $city,
			'address' => $formatted_address,
			'lat' => $lat,
			'lng' => $lng,
			'capacity' => $count
		]
	);
}

$geo_json = array(
	"type" => "FeatureCollection",
	"features" => $result,
);

file_put_contents('data.json', json_encode($geo_json));