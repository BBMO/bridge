<?php
    include("./php/head.php");
    include ("./php/controller.php");

    if( !isset( $_SESSION["turno"] ) ) $_SESSION["turno"] = 2;
    if( $_SESSION["login"] ) {
        if( !isset( $_SESSION["matriz"] ) ){
            fillMatriz();
        }
    }
    else {
        if( $_SERVER["HTTP_HOST"] == "localhost" ) {
            header('Location: /bridge_game/');
        }
        else {
            header('Location: /');
        }
    }
?>

<body>
    <header class="board-header">
        <div class="container">
            <div class="col-6">
                <h1>BRIDGE</h1>
            </div>

            <div class="col-6 right-header">
                <div>
                    <form action="<?php if( $_SERVER["HTTP_HOST"] == "localhost" ): ?>/bridge_game/<?php else: ?>/<?php endif;?>" method="post">
                        <input type="hidden" name="logout" value="true">
                        <button type="submit" class="btn btn-primary">Nuevo Juego</button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <div class="game-container">
        <div class="board-container">
            <?php
                gameOver();
                validatePosition();
            ?>
        </div>
        <div class="right-header-turn">
            <?php if( $_SESSION["turno"] == 1) : ?>
                <div class="box blue-box"></div>
            <?php else : ?>
                <div class="box red-box"></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>