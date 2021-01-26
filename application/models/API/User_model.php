<?php

class User_model extends CI_Model
{
    public function check_user($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }

    public function insertdata($data)
    {
        return $this->db->insert('users', $data);
    }

    public function login()
    {
        $email = $_POST['email'];
        return $this->db->get_where('users', ['email' => $email])->result_array();
    }

    public function contoh($id = null)
    {
        if ($id === null) {

            return $this->db->get('users')->result_array();
        } else {
            return $this->db->get_where('users', ['id' => $id])->result_array();
        }
    }

    public function create_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function fetch_user($email = null)
    {
        if ($email === null) {
            return $this->db->get('users')->result_array();
        } else {
            return $this->db->get_where('users', ['email' => $email])->result_array();
        }
    }
}
