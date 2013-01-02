<?php

// Longitude, Latitude
$boundNW = array(-46.770, -23.4947);
$boundSE = array(-46.438, -23.7065);

// In Degrees
$minAngle = 2;
$maxAngle = 20;

// In Meters
$minRadius = 500;
$maxRadius = 1000;

$numberPolygons = 10;

for ($i = 0; $i < $numberPolygons; $i++) {
    $currentAngle = -180;
    $polygon = array();
    $firstPoint = null;
    $center = getRandomCenter($boundNW, $boundSE);
    while ($currentAngle <= 180 - $maxAngle) {
        $angle = getRandomValue($minAngle, $maxAngle, 2);
        $radius = getRandomValue($minRadius, $maxRadius, 3);
        $currentAngle += $angle;
        $point = getCoordinates($center, $currentAngle, $radius);
        if (!$firstPoint) {
            $firstPoint = $point[0] . ' ' . $point[1];
        }
        $polygon[] = $point[0] . ' ' . $point[1];
    }
    $polygon[] = $firstPoint;

    echo 'POLYGON(( ';
    echo implode(',', $polygon);
    echo ' ))';
    echo "\n";
}

function getRandomValue($minValue, $maxValue, $decimals) {
    $rand = mt_rand($minValue * pow(10, $decimals), $maxValue * pow(10, $decimals));
    
    return $rand/pow(10, $decimals);
}

function getCoordinates($center, $angle, $radius)
{
    $point = array();
    $radians = deg2rad($angle);
    $lngDist = cos($radians) * $radius;
    $lngDegree = getLatitudeDegreeInKm($center[1]);
    $point[0] = round($center[0] + ($lngDist / ($lngDegree * 1000)), 5);
    $latDist = sin($radians) * $radius;
    $latDegree = getLatitudeDegreeInKm($center[1]);
    $point[1] = round($center[1] + ($latDist / ($latDegree * 1000)), 5);
    
    return $point;
}

function getRandomCenter($boundNW, $boundSE) {
    $minLat = min($boundSE[1], $boundNW[1]);
    $maxLat = max($boundSE[1], $boundNW[1]);
    $minLng = min($boundSE[0], $boundNW[0]);
    $maxLng = max($boundSE[0], $boundNW[0]);
    
    $lat = getRandomValue($minLat, $maxLat, 5);
    $lng = getRandomValue($minLng, $maxLng, 5);
    
    return array($lng, $lat);
}


function getLongitudeDegreeInKm($latitude) {
    $latitudeInRadians = deg2rad($latitude);
    $longitudeDegree = 111.41288 * cos($latitudeInRadians) - 0.09350 * cos(3 * $latitudeInRadians) + 0.00012 * cos(5 * $latitudeInRadians);
    
    return $longitudeDegree;
}

function getLatitudeDegreeInKm($latitude) {
    $latitudeInRadians = deg2rad($latitude);
    $latitudeDegree = 111.13295 - 0.55982 * cos(2 * $latitudeInRadians) + 0.00117 * cos(4 * $latitudeInRadians);
    
    return $latitudeDegree;
}

