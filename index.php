<?php
ini_set("memory_limit","1024M");
require_once('cron/apikey.php');
/* auctions.json is approx 22 MB */

echo '
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
';
echo '
<script>var whTooltips = {colorLinks: false, iconSize: "small", iconizeLinks: true, renameLinks: true};</script>
<script src="https://wow.zamimg.com/widgets/power.js"></script>
';
echo '<style>
* {font-family:Verdana,sans-serif; font-size:14px; } 
body { background:#f9f9f9; color:#444; }
table td{padding:2px;} 
/* table td a { text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black !important; } */
table td a { color:#444; }
table tr:hover {background:rgba(0,0,0,0.2); }
.color_gold { color: gold; font-size:14px; font-weight:bold; }
.color_silver { color: silver; font-size:14px; font-weight:bold; }
.color_copper { color: orange; font-size:14px; font-weight:bold; }
</style>';

$realm = 'ravencrest';
if(isset($_GET['realm'])) {
    $realm = $_GET['realm'];
}
else {
    $realm = 'ravencrest';
}



if(file_exists('./cache/auctions/'.$realm.'.json')) {
    $auctions = json_decode(file_get_contents('./cache/auctions/'.$realm.'.json'));
}


/* List auctions */

echo '<table id="tbl_auctions">
<thead>
    <tr>
        <!--<th>ID</th>-->
        <th>iLevel</th>
        <th>Item</th>
        <th>Qty</th>
        <th>Bid</th>
        <th>Buyout</th>
        <th>Time left</th>
        <th>Owner</th>
    </tr>
</thead>
<tbody>';

$i=0;
foreach($auctions->auctions as $a) {
    $iteminfo = '';
    if(!file_exists('./cache/items/'.$a->item.'.json')) {
        $iteminfo = json_decode(file_get_contents('https://eu.api.battle.net/wow/item/'.$a->item.'?locale=en_GB&apikey='.$APIKEY));
        file_put_contents('./cache/items/'.$a->item.'.json', json_encode($iteminfo));
    }
    else {
        $iteminfo = json_decode(file_get_contents('./cache/items/'.$a->item.'.json'));
    }
    $bid = [];
    $bid['c'] = $a->bid % 100;
    $bid['s'] = $a->bid / 100 % 100;
    $bid['g'] = number_format($a->bid / 10000, 0);
    $buyout = [];
    $buyout['c'] = $a->buyout % 100;
    $buyout['s'] = $a->buyout / 100 % 100;
    $buyout['g'] = number_format($a->buyout / 10000, 0);
    echo '<tr>
        <!--<td>'.$a->auc.'</td>-->
        <td>'.$iteminfo->itemLevel.'</td>
        <td><a href="#" data-wowhead="item='.$a->item.'">'.$iteminfo->name.'</a></td>
        <td><span title="Max stack: '.$iteminfo->stackable.'">'.$a->quantity.'</span></td>
        <td>'.$bid['g'].'<span class="color_gold">g</span> '.$bid['s'].'<span class="color_silver">s</span> '.$bid['c'].'<span class="color_copper">c</span></td>
        <td>'.$buyout['g'].'<span class="color_gold">g</span> '.$buyout['s'].'<span class="color_silver">s</span> '.$buyout['c'].'<span class="color_copper">c</span></td>
        <td>'.@ucfirst(strtolower((explode('_', $a->timeLeft)[0]).' '.ucfirst(explode('_', $a->timeLeft)[1]))).'</td>
        <td>'.$a->owner.'</td>
        
    </tr>';
    if($i > 30) { break; }
    $i++;
}
echo '</tbody>
</table>';

?>
<script>
setTimeout(() => {
    $(document).ready( function () {
        $('#tbl_auctions').DataTable();
    } );
}, 100);
</script>

<?php


/* 
tdClass Object
(
    [realms] => Array
        (
            [0] => stdClass Object
                (
                    [name] => Ravencrest
                    [slug] => ravencrest
                )

        )

    [auctions] => Array
        (
            [0] => stdClass Object
                (
                    [auc] => 376182382
                    [item] => 108996
                    [owner] => Hotnanny
                    [ownerRealm] => Ravencrest
                    [bid] => 844640
                    [buyout] => 844640
                    [quantity] => 20
                    [timeLeft] => VERY_LONG
                    [rand] => 0
                    [seed] => 0
                    [context] => 0
                )
 
 
 Class ID	Class Name	englishClass
1	Warrior	WARRIOR
2	Paladin	PALADIN
3	Hunter	HUNTER
4	Rogue	ROGUE
5	Priest	PRIEST
6	Death Knight	DEATHKNIGHT
7	Shaman	SHAMAN
8	Mage	MAGE
9	Warlock	WARLOCK
10	Monk	MONK
11	Druid	DRUID
12	Demon Hunter	DEMONHUNTER



 
                */