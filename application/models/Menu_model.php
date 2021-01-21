<?php

class Menu_model extends CI_Model
{
    var $table = 'user_menu'; //nama tabel dari database
    var $column_order = array('id', 'menu'); //Sesuaikan dengan field
    var $column_search = array('menu'); //field yang diizin untuk pencarian 
    var $order = array('menu' => 'asc'); // default order 

    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_all_menu()
    {
        return $this->db->get('user_menu')->result_array();
    }

    public function addMenu($data)
    {
        return $this->db->insert('user_menu', $data);
    }

    public function deleteMenu($id_menu)
    {
        return $this->db->delete('user_menu', array('id' => $id_menu));
    }

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

    public function get_sub_menu($id_menu)
    {


        $querySubMenu = "SELECT *
               FROM `user_sub_menu` JOIN `user_menu` 
                 ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
              WHERE `user_sub_menu`.`menu_id` = $id_menu
                AND `user_sub_menu`.`is_active` = 1
        ";
        $subMenu = $this->db->query($querySubMenu)->result_array();
        return $subMenu;
    }
}
