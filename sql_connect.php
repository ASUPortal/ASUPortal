<?php
    /**
     * Переделано.
     *
     * Здесь теперь хранятся только настройки для подключения к MySQL, остальные настройки
     * хранятся в таблице settings.
     */
    require_once("sql_connect_credentials.php");

    /**
     * Стартуем сессию. Для совместимости между версиями портала она стартуется здесь.
     * Хотя и раньше она здесь же стартовала
     */
    if (!isset($_SESSION)) {
        session_start();
    }

    global $sql_connect;
    $sql_connect = mysql_connect($sql_host, $sql_login, $sql_passw);
    if(!$sql_connect) {
        echo '<div class=main>Не могу соединиться с сервером Базы Данных. <font color=red>Дальнейшая работа невозможна.</font></div>';
        exit();
    }

    if(mysql_select_db($sql_base, $sql_connect) === false) {
        echo '<div class=main>Не могу выбрать базу данных портала. <font color=red>Дальнейшая работа невозможна.</font></div>';
        exit();
    }
    mysql_query("SET NAMES utf8", $sql_connect);
    mysql_query('SET SQL_LOG_BIN =1', $sql_connect);

    if (!isset($files_path)) {
        $files_path = "";
    }
    require_once($files_path."setup.php");
    require ($files_path.'funcs_php.php');