<?php
include('db_connection.php');
?>
<?php
function get_content($token, $menu) {
    global $dbh;
    $sql = "SELECT * from bots where Token='" . $token . "'";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $array['bot_id'] = $result->Bot_ID;
            $id = $result->Bot_ID;
            $array['token'] = $result->Token;
            $array['token_name'] = $result->Name;
            $array['bot_description'] = $result->Description;

        }

    }
    $sql = "SELECT * from menus where Bot_ID=" . $id . " and Menu_ID=" . $menu;
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $array['Menu_id'] = $result->Menu_ID;
            $array['menu_name'] = $result->Name;
            $array['menu_description'] = $result->Description;

        }

    }


    $sql = "SELECT * from sub_menus where Menu_ID=" . $menu;
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        $count = 0;
        foreach ($results as $result) {
            /*
        $array['sub_menu_name']=$result->Name;
        $array['replay']=$result->Replay;
        $array['Content']=$result->Content;
        */

            $sub_menu_name[$count] = $result->Name;
            $replay[$count] = $result->Replay;
            $content[$count] = $result->Content;
            $count++;


        }
    }
    $array['sub_menu_name'] = json_encode($sub_menu_name);
    $array['replay'] = json_encode($replay);
    $array['Content'] = json_encode([$count]);

    $res = json_encode($array);
    echo $res;

}

get_content('Efriem', 1);


?>

