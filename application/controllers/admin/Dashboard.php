<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        // user_menu();
    }

    public function index()
    {

        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),
            // 'subMenu' => user_sub_menu($id_menu),
            'title' => 'Admin'
        ];


        $this->template->load('templates/admin/v_index', 'admin/v_content', $data);
    }


    public function menu_management()
    {
        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),
        ];
        $data['title'] = 'Menu Management';
        // $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {
            $this->template->load('templates/admin/v_index', 'admin/v_menu', $data);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added!</div>');
            redirect('menu');
        }
    }

    public function addMenu()
    {
        $data = [
            'menu_name' => $_POST['menu_name'],
            'created_at' => time()
        ];

        return $this->menu->addMenu($data);
    }

    public function deleteMenu()
    {
        $id_menu = $_POST['id_menu'];
        return $this->menu->deleteMenu($id_menu);
    }

    public function role_management()
    {

        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),

            'title' => 'Admin'
        ];
        $this->template->load('templates/admin/v_index', 'admin/v_role', $data);
    }
}
