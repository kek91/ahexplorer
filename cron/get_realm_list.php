<?php
require_once('../init.php');

$url = 'https://eu.api.battle.net/wow/realm/status?locale=en_GB&apikey='.$APIKEY;

$json = json_decode(file_get_contents($url));

$realms = [];
$i_max = count($json->realms);
for($i=0; $i<$i_max; $i++) {
    $realms[$i]['name'] = $json->realms[$i]->name;
    $realms[$i]['slug'] = $json->realms[$i]->slug;
    $realms[$i]['population'] = $json->realms[$i]->population;
    $realms[$i]['locale'] = $json->realms[$i]->locale;
    $realms[$i]['battlegroup'] = $json->realms[$i]->battlegroup;
}

file_put_contents('./../cache/realms.json', json_encode($realms));

/*
[0] => stdClass Object
        (
            [type] => normal
            [population] => high
            [queue] => 
            [status] => 1
            [name] => Aegwynn
            [slug] => aegwynn
            [battlegroup] => Misery
            [locale] => de_DE
            [timezone] => Europe/Paris
            [connected_realms] => Array
                (
                    [0] => aegwynn
                )

        )
*/