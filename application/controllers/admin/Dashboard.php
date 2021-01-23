<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();

        $this->load->model('Menu_model', 'menu');
        $this->load->model('Submenu_model', 'submenu');
        $this->load->model('Roleaccess_model', 'role');
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

    public function getEditMenu()
    {
        $id_menu = $_POST['id_menu'];
        $data = array(
            'menu' => $this->menu->getDataById($id_menu),
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function submitEditMenu()
    {
        $data = [
            'id' => $_POST['id_edit_name'],
            'menu' => $_POST['menu_edit_name']
        ];

        $this->menu->submitEdit($data);

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
                $row[] = '<button class="btn btn-outline-danger btn-sm" id="btnDeleteMenu" value="' . $id_c . '"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-outline-info btn-sm" id="btnEditMenu" data-id="' . $id_c . '" value="' . $field->menu . '"><i class="fas fa-edit"></i></button>';
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->menu->count_all(),
                "recordsFiltered" => $this->menu->count_filtered(),
                "data" => $data,
            );

            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function submenu_management()
    {
        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),
            'title' => 'Submenu Management',
            'submenu' => $this->submenu->get_sub_menu()
        ];

        $this->template->load('templates/admin/v_index', 'admin/v_submenu', $data);
    }

    public function addSubMenu()
    {
        $data = [
            'menu_title' => $_POST['submenu_name'],
            'menu_id' => $_POST['submenu_id'],
            'link_url' => $_POST['url_name'],
            'icon_sub' => $_POST['icon_sub'],
            'is_active' => $_POST['is_active'],
            'created_at' => time()
        ];

        $this->submenu->addSubMenu($data);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function deleteSubMenu()
    {
        $id_menu = $_POST['id_menu'];
        $this->submenu->deleteSubMenu($id_menu);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($data);
    }

    public function getEditSubMenu()
    {
        $id_menu = $_POST['id_menu'];
        $data = array(
            'menu' => $this->submenu->getDataById($id_menu),
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function submitEditSubMenu()
    {
        $data = [
            'id' => $_POST['id_edit_submenu'],
            'menu_title' => $_POST['submenu_edit_name'],
            'menu_id' => $_POST['submenu_edit_id'],
            'link_url' => $_POST['edit_url_name'],
            'icon_sub' => $_POST['edit_icon_sub'],
            'is_active' => $_POST['edit_is_active']
        ];

        $this->submenu->submitEdit($data);

        $data = array(

            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($data);
    }

    public function serverside_get_submenu()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->submenu->get_datatables();
            $submenu = $this->submenu->get_sub_menu();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                if ($field->is_active == 0) {
                    $is_active = '<span class="badge badge-info">Not Active</span>';
                } else {
                    $is_active = '<span class="badge badge-danger">Active</span>';
                }
                $row = array();
                $id_c = $field->id_sub;
                $row[] = $no;
                $row[] =  $field->menu_title;
                $row[] = $field->menu;
                $row[] = $field->link_url;
                $row[] = $field->icon_sub;
                $row[] = $is_active;
                $row[] = '<button class="btn btn-outline-danger btn-sm" id="btnDeleteSubMenu" value="' . $id_c . '"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-outline-info btn-sm" id="btnEditSubMenu" data-id="' . $id_c . '" value="' . $field->menu_id . '"><i class="fas fa-edit"></i></button>';
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->submenu->count_all(),
                "recordsFiltered" => $this->submenu->count_filtered(),
                "data" => $data,
                "field" => $field,
            );

            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function role_management()
    {
        $data = [
            'user_session' => $this->session->userdata(),
            'menu_title' => user_menu(),
            // 'role' => $this->role->getById($this->session->userdata('roles')),
            'role_name' => $this->role->getRoleName($this->session->userdata('roles')),
            'title' => 'Role Management'
        ];

        $this->template->load('templates/admin/v_index', 'admin/v_role', $data);
    }

    public function viewRole()
    {
        $id_role = $_POST['id_role'];

        $data = array(
            'menu_title' => user_menu(),
            'role' => $this->db->get_where('user_role_access', ['id' => $id_role])->row_array(),
            // 'role' => $this->role->getById($this->session->userdata('roles')),
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($data);
    }

    public function addRole()
    {
        $data = [
            'role_access' => $_POST['role_name'],
            // 'is_active' => 1,
            'created_at' => time()
        ];

        $this->role->addRole($data);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function deleteRole()
    {

        $id = $_POST['id_role'];
        $this->role->deleteRole($id);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function getEditRole()
    {
        $id = $_POST['id_role'];

        // $this->role->getById($id);

        $data = array(
            'role' => $this->role->getById($id),
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($data);
    }

    public function submitRoleAccess()
    {
    }

    public function serverside_role_access()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->role->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();
                $id_c = $field->id;
                $row[] = $no;
                $row[] = $field->role_access;
                $row[] = '<button class="btn btn-outline-warning btn-sm" id="btnViewRole" value="' . $id_c . '"><i class="fas fa-eye"></i></button>
                <button class="btn btn-outline-info btn-sm" id="btnEditRole" data-id="' . $id_c . '" value="' . $field->role_access . '"><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger btn-sm" id="btnDeleteRole" value="' . $id_c . '"><i class="fas fa-trash"></i></button>';
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->role->count_all(),
                "recordsFiltered" => $this->role->count_filtered(),
                "data" => $data,
            );

            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }
}
