<!doctype html>
<html>
<head></head>
<body>
    <form method="get" action="affichage.php">
        <input type="text" name="code">
        <label for="code">code pme</label>

        <input type="submit">
    </form>
    <?php
        include "trafic_api.php";

        $code = null;
        if (isset($_GET["code"])) {
            $code = $_GET["code"];
        }

        $trafic = Trafic::getInstance();
        $ids = $trafic->idFromAxe($code);
        foreach($ids as $id) {
            $data = $trafic->data($id);
            echo "<p>$id -> flow: {$data["flow"]}, speed: {$data["speed"]}";
        }
    ?>
</body>
</html>


