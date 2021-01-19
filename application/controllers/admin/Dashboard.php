<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function index()
    {

        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $data['tes'] = 'Tes';
        $data['login'] = $this->session->userdata('is_login');
        $data['user_session'] = $this->session->userdata();

        $data = [
            'user_session' => $this->session->userdata(),
        ];
        $this->template->load('templates/admin/v_index', 'admin/v_content', $data);
    }
}
