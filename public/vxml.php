<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';

$service = new Storage($db);

$tips = $service->getRandom(1);
$tip = array_pop($tips);

error_log('got call, sent tip: ' . $tip);
?>
<vxml version = '2.1'>
    <form>
        <block>
            <prompt>
                Here's your tip: <?php echo $tip ?>
            </prompt>
        </block>
    </form>
</vxml>