<?php

/**
 * Created by PhpStorm.
 * User: BENGEOS
 * Date: 10/21/17
 * Time: 10:03 PM
 */
require_once "DB_Access.php";
class DB_API
{
    private $connection;
    private $database;

    public function __construct()
    {
        $this->database = new DB_Access();
        $this->connection = $this->database->pass_connection();
        $this->connection->query("USE bot");
    }
    public function log_error($error_string){

    }
    public function get_menus(){
        try {
            $sql = "SELECT * FROM menus";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            return $set;
        } catch (PDOException $e) {
            $this->log_error($e);
        }
    }
    public function get_starter_menu($bot_token){
        $bot = $this->get_bot_by_token($bot_token);
        if($bot != null){
            try {
                $sql = "SELECT * FROM menus WHERE menus.Is_Starter = 1 AND menus.Bot_ID = :bot_id";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindvalue(':bot_id', $bot['Bot_ID'], PDO::PARAM_INT);
                $stmt->execute();
                $set = $stmt->fetch();
                if($set != null){
                    $new_bot_menu = [];
                    $new_bot_menu['Name'] = $set['Name'];
                    $new_bot_menu['Description'] = $set['Description'];
                    $new_bot_menu['Sub_Menus'] = $this->get_sub_menus_by_menu_id($set['Menu_ID']);
                    return $new_bot_menu;
                }
                return null;
            } catch (PDOException $e) {
                $this->log_error($e);
                return null;
            }
        }else{
            return null;
        }
    }
    public function get_bot_menu($bot_token, $menu_id){
        $bot = $this->get_bot_by_id($bot_token);
        if($bot != null){
            try {
                $sql = "SELECT * FROM menus WHERE menus.Menu_ID = :menu_id AND menus.Bot_ID = :bot_id";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindvalue(':menu_id', $menu_id, PDO::PARAM_INT);
                $stmt->bindvalue(':bot_id', $menu_id, PDO::PARAM_INT);
                $stmt->execute();
                $set = $stmt->fetch();
                if($set != null){
                    $new_bot_menu = [];
                    $new_bot_menu['Name'] = $set['Name'];
                    $new_bot_menu['Description'] = $set['Description'];
                    $new_bot_menu['Sub_Menus'] = $this->get_sub_menus_by_menu_id($set['Menu_ID']);
                    return $new_bot_menu;
                }
                return null;
            } catch (PDOException $e) {
                $this->log_error($e);
                return null;
            }
        }else{
            return null;
        }
    }
    public function get_bot_menu_($bot_token, $menu_name){
        $bot = $this->get_bot_by_token($bot_token);
        if($bot != null){
            try {
                $sql = "SELECT * FROM menus WHERE menus.Name = :m_name AND menus.Bot_ID = :bot_id";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindvalue(':m_name', $menu_name, PDO::PARAM_STR);
                $stmt->bindvalue(':bot_id', $bot['Bot_ID'], PDO::PARAM_STR);
                $stmt->execute();
                $set = $stmt->fetch();
                if($set != null){
                    $new_bot_menu = [];
                    $new_bot_menu['Name'] = $set['Name'];
                    $new_bot_menu['Description'] = $set['Description'];
                    $new_bot_menu['Sub_Menus'] = $this->get_sub_menus_by_menu_id($set['Menu_ID']);
                    return $new_bot_menu;
                }
                return null;
            } catch (PDOException $e) {
                $this->log_error($e);
                return null;
            }
        }else{
            return null;
        }
    }
    private function get_sub_menus_by_menu_id($menu_id){
        try {
            $sql = "SELECT * FROM sub_menus WHERE Menu_ID = ".$menu_id;
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $new_sub_menu = [];
                $new_sub_menu['Name'] = $row['Name'];
                $new_sub_menu['Content'] = $row['Content'];
                $new_sub_menu['Replay'] = $this->get_menu_by_id($row['Replay']);
                $set[] = $new_sub_menu;
            }
            return $set;
        } catch (PDOException $e) {
            $this->log_error($e);
            return null;
        }
    }
    private function get_menu_by_id($menu_id){
        try {
            $sql = "SELECT * FROM menus WHERE Menu_ID = ".$menu_id;
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = $stmt->fetch();
            return $set;
        } catch (PDOException $e) {
            $this->log_error($e);
            return null;
        }
    }
    private function get_sub_menu_by_id($sub_menu_id){
        try {
            $sql = "SELECT * FROM sub_menus WHERE Sub_Menu_ID = ".$sub_menu_id;
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = $stmt->fetch();
            return $set;
        } catch (PDOException $e) {
            $this->log_error($e);
            return null;
        }
    }
    private function get_bot_by_id($bot_id){
        try {
            $sql = "SELECT * FROM bots WHERE Bot_ID = ".$bot_id;
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = $stmt->fetch();
            return $set;
        } catch (PDOException $e) {
            $this->log_error($e);
            return null;
        }
    }
    private function get_bot_by_token($bot_token){
        try {
            $sql = "SELECT * FROM bots WHERE bots.Token = :bot_token";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':bot_token', $bot_token, PDO::PARAM_STR);
            $stmt->execute();
            $set = $stmt->fetch();
            return $set;
        } catch (PDOException $e) {
            $this->log_error($e);
            return null;
        }
    }
}