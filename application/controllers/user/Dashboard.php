<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Menu_model', 'menu');
    }

    public function index()
    {

        $data['login'] = $this->session->userdata('is_login');
        $data['user_session'] = $this->session->userdata();
        $roles = $this->session->userdata('roles');

        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => $this->menu->get_menu($roles),
            'title' => 'User'
        ];

        $this->template->load('templates/admin/v_index', 'user/v_content', $data);
    }
}
