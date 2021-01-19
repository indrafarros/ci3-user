<?php

class Auth_model extends CI_Model
{
    // Login and get user info
    public function user($email)
    {
        $email = $_POST['email'];
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }

    public function register($data)
    {
        return $this->db->insert('users', $data);
    }

    public function create_account_verification($data, $type)
    {
        $new_data = array(
            'token' => $data['new_token'],
            'create_at' => time()
        );
        if ($type == 'new_token') {
            $this->db->set($new_data);
            $this->db->where('email', $data['email']);
            $this->db->update('user_activation_token');
            return true;
        } else if ($type == 'register') {

            return $this->db->insert('user_activation_token', $data);
        }
    }

    public function get_activation_user($email, $token, $type)
    {
        if ($type == 'email') {
            return $this->db->get_where('user_activation_token', ['email' => $email])->row_array();
        } else if ($type == 'token') {
            return $this->db->get_where('user_activation_token', ['token' => $token])->row_array();
        } else {
            return 'Something wrong!';
        }
    }

    public function verify_email($email, $token, $type)
    {
        if ($type == 'activation_success') {

            $data = array(
                'is_active' => 1
            );
            $this->db->where('email', $email);
            $this->db->update('users', $data);

            $this->db->set('deleted_at', time());
            $this->db->where('email', $email);
            $this->db->update('user_activation_token');
        } else if ($type == 'activation_expired') {
            $this->db->set('deleted_at', time());
            $this->db->where('email', $email);
            $this->db->update('user_activation_token');
        }

        return true;
        // $this->db->where('email', $email);
        // $this->db->update('user_activation_token', $email);
    }

    public function change_password()
    {
    }

    public function delete_user_error()
    {
    }
}
