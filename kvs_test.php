<?php
namespace Stanford\KVS;
/** @var \Stanford\KVS\KVS $module */

// A test page for running SPL Lookups

use HtmlPage;

$HtmlPage = new HtmlPage();
$HtmlPage->PrintHeaderExt();

\Plugin::log($_POST);

if (!empty($_POST['key']) && !empty($_POST['pid'])) {
    $result  = $module->getValue($_POST['pid'], $_POST['key']);
}

echo  "This is still a work in progress...";

?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?php echo $module->getModuleName() ?>
        </div>
        <div class="panel-body">
            <?php
                if ($result === null) {
                    echo "Please enter a sunet id to test the SPL lookup module";
                } elseif ($result === false) {
                    echo "Lookup for $uid did not return any results.";
                } else {
                    echo "<h4>Results for <u>$uid</u></h4><pre>" . print_r($result,true) . "</pre>";
                    $uid = "";
                }
            ?>
        </div>
        <div class="panel-footer">
            <form method="POST" action="">
                <div class="input-group col-lg-5">
                    <span class="input-group-addon" id="id_label">Retrieve Key:</span>
                    <input type="text" class="form-control" placeholder="Enter Key" name="key" value="<?php echo $key ?>" aria-describedby="id_label">
                    <input type="text" class="form-control" placeholder="Enter Project ID" name="pid" value="<?php echo $pid ?>" aria-describedby="id_label">
                    <span class="input-group-btn">
                        <button type="submit" name="action" value="get" class="btn btn-primary">Get Value</button>
                    </span>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" id="val_label">Set Value:</span>
                    <input type="text" class="form-control" placeholder="Enter Project ID" name="pid" value="<?php echo $pid ?>" aria-describedby="val_label">
                    <input type="text" class="form-control" placeholder="Enter Key" name="key" value="<?php echo $key ?>" aria-describedby="val_label">
                    <input type="text" class="form-control" placeholder="Enter Value" name="val" value="" aria-describedby="val_label">
                    <span class="input-group-btn">
                        <button type="submit" name="action" value="get" class="btn btn-primary">Set Value</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('input[name="key"]').focus();
    </script>

<?php

$HtmlPage->PrintFooterExt();
