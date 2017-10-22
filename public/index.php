<?php
/**
 * Created by PhpStorm.
 * User: BENGEOS
 * Date: 10/21/17
 * Time: 10:08 PM
 */
require_once("../private/DB_API.php");
$db_api = new DB_API();
if(isset($_POST)){
    if(isset($_POST['bot_token']) && isset($_POST['menu_id'])){
        if($_POST['menu_id'] == 'get_start'){
            echo json_encode($db_api->get_starter_menu($_POST['bot_token']));
        }else{
            echo json_encode($db_api->get_bot_menu_($_POST['bot_token'],$_POST['menu_id']));
        }
    }
}