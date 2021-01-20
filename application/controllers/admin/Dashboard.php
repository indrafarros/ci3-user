<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        user_menu();
    }

    public function index()
    {

        $data['tes'] = 'Tes';
        $data['login'] = $this->session->userdata('is_login');
        $data['user_session'] = $this->session->userdata();

        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),
            'title' => 'Admin'
        ];
        $this->template->load('templates/admin/v_index', 'admin/v_content', $data);
    }
}
