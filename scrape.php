<?php

$locations = ["/nl/opvanglocaties/alkmaar", "/nl/opvanglocaties/almelo", "/nl/opvanglocaties/almere", "/nl/opvanglocaties/alphen-aan-den-rijn", "/nl/opvanglocaties/amersfoort", "/nl/opvanglocaties/apeldoorn", "/nl/opvanglocaties/arnhem-de-berg", "/nl/opvanglocaties/arnhem-zuid-vreedenburgh-0", "/nl/opvanglocaties/azelo", "/nl/opvanglocaties/baexem-1", "/nl/opvanglocaties/bellingwolde", "/nl/bergen-aan-zee", "/nl/opvanglocaties/breda", "/nl/opvanglocaties/budel-cranendonck", "/nl/opvanglocaties/budel-dorplein", "/nl/opvanglocaties/burgum", "/nl/opvanglocaties/delfzijl-1", "/nl/opvanglocaties/den-haag", "/nl/opvanglocaties/den-haag-loosduinen", "/nl/opvanglocaties/den-helder-doggershoek", "/nl/opvanglocaties/den-helder-maaskampkazerne", "/nl/opvanglocaties/den-helder-gezinslocatie", "/nl/opvanglocaties/deventer", "/nl/opvanglocaties/doetinchem", "/nl/opvanglocaties/drachten", "/nl/opvanglocaties/dronten", "/nl/opvanglocaties/echt", "/nl/opvanglocaties/ede", "/nl/opvanglocaties/eindhoven", "/nl/opvanglocaties/emmen", "/nl/opvanglocaties/geeuwenbrug", "/nl/content/azc-gilze-en-rijen", "/nl/opvanglocaties/goes", "/nl/opvanglocaties/grave", "/nl/opvanglocaties/groningen", "/nl/opvanglocaties/gulpen-wittem", "/nl/opvanglocaties/haarlem", "/nl/opvanglocaties/heerhugowaard", "/nl/opvanglocaties/heerlen", "/nl/opvanglocaties/hengelo-ov-avo", "/nl/opvanglocaties/hoogeveen", "/nl/opvanglocaties/katwijk", "/nl/opvanglocaties/kolham", "/nl/opvanglocaties/leersum-0", "/nl/opvanglocaties/leeuwarden", "/nl/opvanglocaties/leiden", "/nl/opvanglocaties/luttelgeest", "/nl/opvanglocaties/maastricht", "/nl/opvanglocaties/middelburg", "/nl/opvanglocaties/musselkanaal", "/nl/opvanglocaties/nijmegen-heumensoord", "/nl/opvanglocaties/nijmegen", "/nl/opvanglocaties/oisterwijk", "/nl/opvanglocaties/ommen", "/nl/opvanglocaties/onnen", "/nl/opvanglocaties/ootmarsum", "/nl/opvanglocaties/oranje", "/nl/opvanglocaties/oude-pekela", "/nl/opvanglocaties/overberg", "/nl/opvanglocaties/overloon", "/nl/rosmalen", "/nl/opvanglocaties/rijswijk-centraal-bureau", "/nl/opvanglocaties/schalkhaar", "/nl/opvanglocaties/scheerwolde", "/nl/opvanglocaties/sint-annaparochie", "/nl/opvanglocaties/stadskanaal", "/nl/opvanglocaties/sweikhuizen", "/nl/ter-apel-col-0", "/nl/opvanglocaties/tilburg", "/nl/opvanglocaties/utrecht", "/nl/opvanglocaties/veenhuizen", "/nl/opvanglocaties/velp", "/nl/opvanglocaties/venlo", "/nl/opvanglocaties/vierhouten", "/nl/opvanglocaties/vledder", "/nl/opvanglocaties/wageningen", "/nl/opvanglocaties/wassenaar", "/nl/opvanglocaties/weert", "/nl/opvanglocaties/winterswijk-0", "/nl/opvanglocaties/zaanstad", "/nl/opvanglocaties/zaanstad-noodopvang", "/nl/opvanglocaties/zeist", "/nl/opvanglocaties/zweeloo-1", "/nl/opvanglocaties/zwolle-ijsselhallen"];

ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 

foreach ($locations as $location) {
	$hash = md5($location);
	$filename = $hash . '.html';

	if (!file_exists($filename)) {
		$location_html = file_get_contents('https://www.coa.nl' . $location);
		file_put_contents($filename, $location_html);
	}

	$doc = new DomDocument;

	// We need to validate our document before refering to the id
	$doc->validateOnParse = true;
}