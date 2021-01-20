<?php

class Menu_model extends CI_Model
{

    public function get_menu($role_id)
    {
        $sql = "SELECT `user_menu`.`id`, `menu`
        FROM `user_menu` JOIN `user_group_menu`
        ON `user_menu`.`id` = `user_group_menu`.`menu_id`
        WHERE `user_group_menu`.`roles_id` = $role_id
        ORDER BY `user_group_menu`.`menu_id` ASC
        ";
        $menu_title = $this->db->query($sql)->result_array();

        return $menu_title;
    }

    public function get_sub_menu()
    {

        //     $menuId = $menu['id'];
        //     $querySubMenu = "SELECT *
        //        FROM `user_sub_menu` JOIN `user_menu` 
        //          ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
        //       WHERE `user_sub_menu`.`menu_id` = $menuId
        //         AND `user_sub_menu`.`is_active` = 1
        // ";
        //     $subMenu = $this->db->query($querySubMenu)->result_array();
        //     return $subMenu;
    }
}
