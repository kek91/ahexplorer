<?php
ini_set("memory_limit","1024M");
require_once('../init.php');

$realms = json_decode(file_get_contents('./../cache/realms.json'));
echo 'Starting...<br>';

//$i = 0;
foreach($realms as $realm) {
    /* if($i > 0) { break; }
    $i++; */

    $ahdata = json_decode(file_get_contents('https://eu.api.battle.net/wow/auction/data/'.$realm->slug.'?locale=en_GB&apikey='.$APIKEY));
    $ahdata = $ahdata->files[0];

    $file_modified = $ahdata->lastModified;
    $file_url = $ahdata->url;

    $auctions = file_get_contents($file_url);
    file_put_contents('./../cache/auctions/'.$realm->slug.'.json', $auctions);

    echo 'Saved to ./../cache/auctions/'.$realm->slug.'.json';

    /* echo 'Last modified: '. date('d.m.Y H:i:s', ($file_modified/1000)).' ('.$file_modified.')<br>';
    echo 'URL: '.$file_url.'<br><br>';
     */

}