<?php
class GoogleGeo {
    public static function buildStaticMap($center, $markers=array(), $width=400, $height=400, $zoom=12, $directions=null) {
        $strMarkers = "";
        foreach($markers as $marker) {
            if (!empty($strMarkers)) $strMarkers .= '|';
            $strMarkers .= urlencode($marker);
        }
        if ($width > 640) $width = 640;
        if (!empty($center)) {
            $center = "&center=".$center;
        }
        if (!empty($strMarkers)) {
            $strMarkers = "&markers=".$strMarkers;
        }
        if ($zoom > 0) {
            $zoom = "&zoom=$zoom";
        }

        $steps = "";
        if (!empty($directions)) {
            foreach($directions['Directions']['Routes'][0]['Steps'] as $step) {
                $lat = $step['Point']['coordinates'][1];
                $lon = $step['Point']['coordinates'][0];
                if (!empty($steps)) $steps .= "|";
                $steps .= $lat.",".$lon;
            }
            if (!empty($steps)) {
                $steps .= "|".$directions['Directions']['Routes'][0]['End']['coordinates'][1].",".$directions['Directions']['Routes'][0]['End']['coordinates'][0];
                $steps = "&path=rgb:0x0000ff,weight:5|".$steps;
            }
        }

        $staticMap = "http://maps.google.com/staticmap?maptype=mobile&size=".$width."x$height&maptype=roadmap&key=".GOOGLE_MAPS_KEY."&sensor=false$strMarkers$center$zoom$steps";
        return $staticMap;
    }

    public static function retrieveDirections ($from, $to) {
        $params = array('key' => GOOGLE_MAPS_KEY, 'output' => 'json', 'q' => "from: $from to: $to");
        $url = "http://maps.google.com/maps/nav";
        $result = HttpHelper::doGET($url, $params);
        $result = json_decode($result, true);
        return $result;
    }
}
?>