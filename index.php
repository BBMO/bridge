<?php
    include("./php/head.php");

    session_start();
    $errors = false;
    if ( isset( $_POST["logout"] ) ) {
        session_destroy();
    }

    if ( isset( $_SESSION["login"] ) ) {
        header('Location: board');
    }

    if( isset( $_POST["dimension"] ) &&  $_POST["dimension"] > 1 && ( $_POST["dimension"] % 2 ) != 0 &&
        isset( $_POST["player"] ) && $_POST["player"][0] != $_POST["player"][1]) {
        $_SESSION["login"] = TRUE;
        $_SESSION["dimension"] = $_POST["dimension"];

        $_SESSION["player"][] = $_POST["player"][0];
        $_SESSION["player"][] = $_POST["player"][1];

        header('Location: board');
    }
    elseif ( isset( $_POST["dimension"] ) || isset($_POST["player"]) ) {
        $errors = true;
    }

?>

<body>

    <div class="page-container">
        <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" class="form-container">
            <?php if ($errors) : ?>
                <div class="alert alert-danger" role="alert" >Errores en el formulario, intente de nuevo</div>
            <?php endif; ?>
            <h1 class="form-title">BRIDGE</h1>
            <div class="form-row">
                <div class="form-group col">
                    <label>Jugador 1</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="btn btn-primary"></span>
                        </div>
                        <input type="text" name="player[]" class="form-control" placeholder="Jugador 1">
                    </div>
                </div>
                <div class="form-group col">
                    <label>Jugador 2</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="btn btn-danger"></span>
                        </div>
                        <input type="text" name="player[]" class="form-control" placeholder="Jugador 2">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Dimension</label>
                <input type="number" min="3" step="2" name="dimension" class="form-control" placeholder="Dimension ( impar )">
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>

</body>
</html>
