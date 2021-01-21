<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();

        $this->load->model('Menu_model', 'menu');
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
            'title' => 'Menu Management'
        ];
        // $data['menu'] = $this->menu->get_all_menu();


        $this->template->load('templates/admin/v_index', 'admin/v_menu', $data);
    }

    public function addMenu()
    {
        $data = [
            'menu' => $_POST['menu_name'],
            'is_active' => 1,
            'created_at' => time()
        ];

        $this->menu->addMenu($data);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function deleteMenu()
    {
        $id_menu = $_POST['id_menu'];
        $this->menu->deleteMenu($id_menu);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($data);
    }

    public function serverside_get_menu()
    {


        if ($this->input->is_ajax_request() == true) {
            $list = $this->menu->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();
                $id_c = $field->id;
                $row[] = $no;
                $row[] = $field->menu;
                $row[] = '<button class="btn btn-outline-danger" id="btnDeleteMenu" value="' . $id_c . '">Delete</button>
                            <button class="btn btn-outline-info" id="btnEditMenu" data-idc="' . $id_c . '" value="' . $field->menu . '">Update</button>';
                $data[] = $row;
            }
            $output = array(

                "draw" => $_POST['draw'],
                "recordsTotal" => $this->menu->count_all(),
                "recordsFiltered" => $this->menu->count_filtered(),
                "data" => $data,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            );

            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function sub_menu_management()
    {
        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),
            'title' => 'Menu Management'
        ];
        $data['menu'] = $this->menu->get_all_menu();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {
            $this->template->load('templates/admin/v_index', 'admin/v_sub_menu', $data);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added!</div>');
            redirect('menu');
        }
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
