<?php

include 'classes/database.php';
include 'classes/secret.php';
include "classes/page.php";

$database = new Database();
$db = $database->connect();

$secret = new Secret($db);

Page::ShowBegin();

?>

<section class="container add-secret">
    <div class="row">
        <div class="col-12">
            <?php
            if (isset($_POST['create'])) {

                $secret->text = $_POST['secretText'];
                $secret->expiresAfterViews = $_POST['expireAfterViews'];
                $secret->expiresAfter = $_POST['expireAfter'];

                if ($secret->setSecret()) {
                    echo '<div class="alert alert-success" role="alert">Sikeres feltöltés! A titok hash kódja: '. $secret->hash .'</div>';
                } 
                else {
                    echo '<div class="alert alert-danger" role="alert">Sikertelen feltöltés!</div>';
                }
            }
            ?>
        </div>
        <div class="col-5">
            <form method="POST" action="index.php">
                <div class="mb-3">
                    <label for="secretText" class="form-label">Secret text</label>
                    <textarea class="form-control" id="secretText" name="secretText" rows="5"></textarea>
                </div>
                <div class="mb-3">
                    <label for="expireAfterViews" class="form-label">Expire after views</label>
                    <input type="number" value="1" min="1" class="form-control" name="expireAfterViews" id="expireAfterViews">
                </div>
                <div class="mb-3">
                    <label for="expireAfter" class="form-label">Expire after</label>
                    <input type="number" value="0" min="0" class="form-control" name="expireAfter" id="expireAfter">
                </div>
                <div class="mb-3">
                    <input name="create" type="submit" class="btn btn-primary" value="Add">
                </div>
            </form>
        </div>
    </div>
</section>
<section class="container get-secret">
    <div class="row">
        <div class="col-5">
            <form>
                <div class="mb-3">
                    <label for="secretHash" class="form-label">Secret hash code</label>
                    <input type="text" class="form-control" id="secretHash" name="secretHash">
                </div>
                <div class="mb-3">
                    <input name="read" type="submit" class="btn btn-primary" value="Get">
                </div>
            </form>
        </div>
        <?php
        if (isset($_GET['read'])) {
            $secret->hash = $_GET['secretHash'];
            if ($secret->getSecretByHash()) {
                $row = array(
                    'hash' => $secret->hash,
                    'secretText' => $secret->text,
                    'remainingViews' => $secret->expiresAfterViews,
                    'expiresAt' => $secret->expiresAfter,
                );
                echo '<div class="col-12 secret-header">
                <div class="row">
                <div class="col-6">Még <b>'. $row['remainingViews'].'</b> alkalommal tekinthető meg a titok.</div>
                <div class="col-6">Még <b>'. $row['expiresAt'] .'</b> dátumig olvasható el.</div>
                </div>
                </div>';
                echo '<div class="col-12 secret-body">'. $row['secretText'] .'</div>';
                echo '<div class="col-12 secret-footer">'. $row['hash'] .'</div>';
            }
            else {
                echo '<div class="col-12 secret-header">
                <div class="row">
                <div class="col-6"></div>
                <div class="col-6"></div>
                </div>
                </div>';
                echo '<div class="col-12 secret-body">Nem létezik ilyen titok!</div>';
                echo '<div class="col-12 secret-footer">'. $secret->hash .'</div>';
            }
        }
        ?>
    </div>
</section>

<?php

Page::ShowEnd();

?>
