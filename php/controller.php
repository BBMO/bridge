<?php
session_start();

function fillMatriz () {

    for ( $i = 0; $i < $_SESSION["dimension"]; $i++ ) {
        for ( $j = 0; $j < $_SESSION["dimension"]; $j++ ) {

            if ( ($i % 2) ) {
                if( ($j % 2) == 0) {
                    $matriz[$i][$j]["player"] = 1; //Player 1
                } else {
                    $matriz[$i][$j]["player"] = 0; //Neutro
                }
            } else {
                if( ($j % 2) == 0) {
                    $matriz[$i][$j]["player"] = 0;
                } else {
                    $matriz[$i][$j]["player"] = 2; //Player 2
                }
            }

        }
    }

    $_SESSION["matriz"] = $matriz;
    $_SESSION["player_win"]["win"] = false;
}

function printMatriz () {

    for ( $i = 0; $i < $_SESSION["dimension"]; $i++ ) {
        echo '<div class="col-container">';
        for ($j = 0; $j < $_SESSION["dimension"]; $j++) {
            switch ( $_SESSION["matriz"][$i][$j]["player"] ) {
                case 1: {
                    $class = "blue-box";
                    break;
                }
                case 2: {
                    $class  = "red-box";
                    break;
                }
                default: {
                    $class  = "brown-box";
                }
            }
            echo "<a href=?row=". $i ."&col=". $j. "><div class='box ". $class . "'></div></a>";
        }
        echo '</div>';
    }

}

function validatePosition () {
    if(isset( $_GET["row"] ) && isset( $_GET["col"] ) ) {
        if ($_GET["row"] >= 0 && $_GET["row"] < $_SESSION["dimension"] &&
            $_GET["col"] >= 0 && $_GET["col"] < $_SESSION["dimension"]) {

            if ($_SESSION["matriz"][$_GET["row"]][$_GET["col"]]["player"] === 0) {
                $_SESSION["matriz"][$_GET["row"]][$_GET["col"]]["player"] = $_SESSION["turno"];
                if( changeTurn() ) {
                    echo '<div class="alert alert-danger" role="alert" >No hay más casillas disponibles, inicie un nuevo juego</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert" >Casilla inválida, seleccione otra</div>';
            }

        } else {
            echo '<div class="alert alert-danger" role="alert" >Parámetros Inválidos</div>';
        }
    }
    printMatriz();
}

function changeTurn () {

    //Cargando data temporal
    $fullBoxes = true;
    $matriz = $_SESSION["matriz"];
    for ($i = 0; $i < $_SESSION["dimension"]; $i++) {
        for ($j = 0; $j < $_SESSION["dimension"]; $j++) {
            $matriz[$i][$j]['checked'] = false;
            if( $matriz[$i][$j]["player"] == 0 ) {
                $fullBoxes = false;
            }
        }
    }

    if ( !$fullBoxes ) {
        $validations = [
            "left" => false,
            "right" => false,
            'top' => false,
            'bottom' => false
        ];


        if ($_SESSION["turno"] === 1) {
            validateWay((int)$_GET["row"], (int)$_GET["col"], $matriz, $validations);
            //print_r($validations);
            if ($validations["left"] && $validations["right"]) {
                $_SESSION["player_win"]["win"] = true;
                $_SESSION["player_win"]["name"] = $_SESSION['player'][0];
            };
            $_SESSION["turno"] = 2;
        } else {
            validateWay((int)$_GET["row"], (int)$_GET["col"], $matriz, $validations);
            //print_r($validations);
            if ($validations["top"] && $validations["bottom"]) {
                $_SESSION["player_win"]["win"] = true;
                $_SESSION["player_win"]["name"] = $_SESSION['player'][1];
            };
            $_SESSION["turno"] = 1;
        }
    }
    gameOver();
    return $fullBoxes;
}

function validateWay ($i, $j, $matriz, &$validations) {
    if( !$matriz[$i][$j]["checked"] ) {

        $matriz[$i][$j]["checked"] = true;

        //Validaciones
        if ( $j == 0 ) {
            $validations["left"] = true;
        }
        if ( $j == ( $_SESSION["dimension"] - 1 ) ) {
            $validations["right"] = true;
        }
        if ( $i == 0 ) {
            $validations["top"] = true;
        }
        if ( $i == ( $_SESSION["dimension"] - 1 ) ) {
            $validations["bottom"] = true;
        }

        //Movimiento recursivo en la matriz
        if ($i < ( $_SESSION["dimension"] - 1 ) && $matriz[$i + 1][$j]["player"] === $_SESSION["turno"]) {
            validateWay($i + 1, $j, $matriz,$validations);
        }
        if ($j < ( $_SESSION["dimension"] - 1 ) && $matriz[$i][$j + 1]["player"] === $_SESSION["turno"]) {
            validateWay($i, $j + 1, $matriz,$validations);
        }
        if ($i > 0 && $matriz[$i - 1][$j]["player"] === $_SESSION["turno"]) {
            validateWay($i - 1, $j, $matriz,$validations);
        }
        if ($j > 0 && $matriz[$i][$j - 1]["player"] === $_SESSION["turno"]) {;
            validateWay($i, $j - 1, $matriz,$validations);
        }

    }

}

function gameOver () {
    if( $_SESSION["player_win"]["win"] ) {
        ob_start();
        ?>
        <div class="modal fade show" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">GAME OVER</h5>
                    </div>
                    <div class="modal-body">
                        <p>El jugador <strong><?php echo $_SESSION["player_win"]["name"] ?></strong> ha ganado</p>
                    </div>
                    <div class="modal-footer">
                        <form action="<?php if( $_SERVER["HTTP_HOST"] == "localhost" ): ?>/bridge_game/<?php else: ?>/<?php endif;?>" method="post">
                            <input type="hidden" name="logout" value="true">
                            <button type="submit" class="btn btn-primary">Nuevo Juego</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        ob_end_flush();
    }
}