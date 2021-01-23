<?php

class Roleaccess_model extends CI_Model
{

    public $table = 'user_role_access'; //nama tabel dari database
    public $column_order = array('id', 'role_acces', 'created_at'); //Sesuaikan dengan field
    public $column_search = array('role_access'); //field yang diizin untuk pencarian 
    public $order = array('role_access' => 'asc'); // default order 
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

    public function addRole($data)
    {
        return $this->db->insert('user_role_access', $data);
    }

    public function deleteRole($id)
    {
        return $this->db->delete('user_role_access', array('id' => $id));
    }

    public function getById($id)
    {
        return $this->db->get_where('user_role_access', ['id' => $id])->row_array();
    }

    public function getRoleName($id)
    {
        $sql = "SELECT user_group_menu.id, user_group_menu.menu_id, user_group_menu.roles_id, user_role_access.id, user_role_access.role_access FROM user_group_menu JOIN user_role_access ON user_group_menu.roles_id = user_role_access.id WHERE user_group_menu.roles_id = '$id' GROUP BY user_group_menu.roles_id
        ";
        return $this->db->query($sql)->row_array();
    }
}
