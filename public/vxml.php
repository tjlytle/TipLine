<?php
use Tips\Service;
require_once __DIR__ . '/../config/bootstrap.php';

$service = new Service($mongo);

$tips = $service->getRandom(1);
$tip = array_pop($tips);

?>
<vxml version = "2.1" >
    <form>
        <block>
            <prompt>
                Here's your tip. <?php echo $tip ?>
            </prompt>
        </block>
    </form>
</vxml>