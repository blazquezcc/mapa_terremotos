<?php
// by David Jiménez Villarejo 
// https://github.com/TheBanusco10/earthquakes-map

header('Content-type: application/json');

$feed = simplexml_load_file('http://www.ign.es/ign/RssTools/sismologia.xml');

$array = [];

$namespaces = $feed->getNamespaces(true);

foreach ($feed->channel->item as $item) {

    $title = $item->title;

    preg_match("/\d+\/\d+\/\d{4}/", $title, $date);
    preg_match("/\d+\:\d+\:\d+/", $title, $time);
    preg_match("/magnitud \d.\d/", $item->description, $magnitude);

    $magnitude = substr((string)$magnitude[0], 9);
    $description = (string)$item->description;
    $location = substr($description, strpos($description, "en") + 3);
    $location = substr($location, 0, strpos($location, "en") - 1);

    $namespace = $item->children($namespaces["geo"]);

    array_push($array, [
        'title' => (string) $title,
        'date' => (string)$date[0],
        'time' => (string)$time[0],
        'link' => (string)$item->link,
        'description' => $description,
        'magnitude' => $magnitude,
        'location' => $location,
        'lat' => (string)$namespace->lat,
        'long' => (string)$namespace->long
    ]);
}

echo json_encode($array);
