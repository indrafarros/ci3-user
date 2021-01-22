<?php

class Submenu_model extends CI_Model
{

    // public $table = 'user_sub_menu'; //nama tabel dari database
    public $column_order = array('id', 'menu_id', 'menu_title', 'link_url', 'icon_sub', 'is_active'); //Sesuaikan dengan field
    public $column_search = array('menu_title'); //field yang diizin untuk pencarian 
    public $order = array(''); // default order 
    function __construct()
    {
        parent::__construct();
    }


    private function _get_datatables_query()
    {
        $this->db->select('user_sub_menu.id as id_sub, user_sub_menu.menu_id, user_sub_menu.menu_title, user_sub_menu.link_url, user_sub_menu.icon_sub, user_sub_menu.is_active, user_menu.id, user_menu.menu')->from('user_sub_menu')->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');
        // $this->db->from($this->table);




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

            // $this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');
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
        $this->db->from('user_sub_menu');
        return $this->db->count_all_results();
    }

    public function get_all_menu()
    {
        return $this->db->get('user_menu')->result_array();
    }

    public function addSubMenu($data)
    {
        return $this->db->insert('user_sub_menu', $data);
    }

    public function deleteSubMenu($id_menu)
    {
        return $this->db->delete('user_sub_menu', array('id' => $id_menu));
    }

    public function getDataById($id_menu)
    {
        return $this->db->get_where('user_sub_menu', ['id' => $id_menu])->row_array();
    }

    public function submitEdit($data)
    {

        $id = $data['id'];
        // $id = $data['id'];
        // $menu = $data['menu'];
        // $this->db->set('menu', $menu);
        // $this->db->where('id', $id);
        // return $this->db->update('user_menu');
        $this->db->where('id', $id);
        $this->db->update('user_sub_menu', $data);
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

    public function get_sub_menu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
        FROM `user_sub_menu` JOIN `user_menu`
        ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
      ";
        return $this->db->query($query)->result_array();
    }
}
