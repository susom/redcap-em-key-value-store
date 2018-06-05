<?php
namespace Stanford\KVS;
/** @var \Stanford\KVS\KVS $module */

// A test page for running SPL Lookups

use HtmlPage;

$HtmlPage = new HtmlPage();
$HtmlPage->PrintHeaderExt();



$select_pid = isset($_POST['select_pid']) ? intval($_POST['select_pid']) : null;
$pid = isset($_POST['pid']) ? intval($_POST['pid']) : null;
$getkey = isset($_POST['getkey']) ? $_POST['getkey'] : null;
$setkey = isset($_POST['setkey']) ? $_POST['setkey'] : null;
$setval = isset($_POST['setval']) ? $_POST['setval'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : null;




// FETCH ALL PROJECTS
$projects = array();
$sql = "SELECT project_id, app_title FROM redcap_projects";
$q = db_query($sql);
while ($row = db_fetch_assoc($q)) {
    $projects[$row['project_id']] = $row['app_title'];
}

// BUILD PROJECT SELECT
$options = array("<option value=''>Select a Project</option>");
foreach ($projects as $project_id => $title) {
    $options[] = "<option value='$project_id'" .
        ( $project_id == $select_pid ? " selected" : "" ).
        ">[$project_id] $title</option>";
}
$select = "<select style='height: 32px;' id='project_select' class='form_control' name='select_pid'>" . implode("",$options) . "</select>";

if ($action == "get" && !empty($getkey)) {
    $result = $module->getValue($select_pid, $getkey);
    $setkey = $getkey;
    $setval = $result;
}

if ($action == "set" && !empty($setkey)) {
    $result = $module->setValue($select_pid, $setkey, $setval);
}

?>
    <form method="POST" id="form0" action="">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?php echo $module->getModuleName() ?></h4>
            </div>
            <div class="panel-body">
                <div>
                    <p>This tool will help you decode or encode key-value pairs for a particular project.  Keep in mind:</p>
                    <ul>
                        <li>There is no confirmation - when you press encode it will replace any existing value.</li>
                        <li>The encoded versions are salt-specific so you cannot transfer the encoded value to a server with a different salt</li>
                    </ul>
                </div>
                <div class="input-group col-lg-5">
                    <span class="input-group-addon" id="id_label">Select a project:</span>
                    <?php echo $select ?>
                </div>

<?php
    if (!empty($select_pid)) {
        // BUILD A TABLE OF KEY-VALUE PAIRS
        $sql = "SELECT * FROM redcap_external_module_settings WHERE project_id = '$select_pid'";
        $q = db_query($sql);
        $rows = array();
        while ($row = db_fetch_assoc($q)) {
            $rows[]="<tr><td>" . $row['key'] . "</td>".
            "<td>" . $row['value'] . "</td>" .
            "<td><span data-val='" . $row['key'] . "' class='btn btn-primary btn-xs decode'>Decode</span></td></tr>";
        }
?>
                <div class="input-group margin-20">
                    <span class="input-group-addon" id="val_label">Add/Update:</span>
                    <input type="text" class="fifty form-control" placeholder="Key" name="setkey" value="<?php echo $setkey ?>" aria-describedby="val_label">
                    <input type="text" class="fifty form-control" placeholder="Value" name="setval" value="<?php echo $setval ?>" aria-describedby="val_label">
                    <span class="input-group-btn">
                            <button type="submit" name="action" value="set" class="btn btn-primary">Encode</button>
                    </span>
                </div>

                <div class="margin-20">
                    <table id='table' class="table">
                        <thead>
                            <tr><th>Key</th><th>Value</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php echo implode("",$rows); ?>
                        </tbody>
                    </table>
                </div>

                <div class="hidden input-group margin-20">
                    <span class="input-group-addon" id="id_label">Get Value:</span>
                    <input type="text" class="form-control" placeholder="Key" name="getkey" value="<?php echo $getkey ?>" aria-describedby="id_label">
                    <span class="input-group-btn">
                            <button type="submit" name="action" value="get" class="btn btn-primary">Decode</button>
                    </span>
                </div>
            </div>
        </div>
    </form>
<?php
    }

    // DEBUG POST
    if (!empty($_REQUEST['debug'])) echo "<pre>" . print_r($_POST,true) . "</pre>";
?>

    <script>

        $('input[name="key"]').focus();

        $('#project_select').bind('change', function() {
            $('form').submit();
        });

        $('span.decode').bind('click', function() {
            var keyval = $(this).data('val');
            $('input[name="getkey"]').val(keyval);
            $('button[value="get"]').click();
        });

    </script>
    <style>
        .fifty {width:50% !important;}
        .margin-20 {margin:20px 0;}
        #table {width:100%;}
        th {font-weight:bold;}
    </style>


<?php

$HtmlPage->PrintFooterExt();

