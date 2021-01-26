<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class UserController extends RestController
{


    public function __construct()
    {
        parent::__construct();

        $this->load->model('API/User_model', 'user');
    }

    public function index_get()
    {

        $id = $this->get('id');
        if ($id === null) {

            $data = $this->user->contoh();
        } else {
            $data = $this->user->contoh($id);
        }

        if ($data) {
            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => $data
            ], 200);
        } else {
            $this->response([
                'status' => true,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function login_post()
    {
        $data = array(
            'email' => $this->post('email'),
            'password' => $this->post('password')
        );
    }

    public function register_post()
    {
        // $jsonArray = json_decode(file_get_contents('php://input'), true);

        $first_name = strip_tags($_POST['first_name']);
        $last_name = strip_tags($_POST['last_name']);
        $email = strip_tags($_POST['email']);
        $password = $_POST['password'];
        $photo_path = 'default.png';
        $phone = '';
        $create_at = time();
        $update_at = time();
        $roles = 1;
        $delete_at = '';
        $last_login = 0;

        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)) {
            // $data['email'] = $email;
            $user_check = $this->user->check_user($email);

            if ($user_check > 0) {
                $this->response([
                    'status' => false,
                    'message' => 'Email already registered'
                ], 404);
            } else {
                $data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'photo_profile_path' => $photo_path,
                    'phone' => $phone,
                    'create_at' => $create_at,
                    'update_at' => $update_at,
                    'is_active' => 0,
                    'roles' => $roles,
                    'deleted_at' => $delete_at,
                    'last_login' => $last_login,
                ];

                $insertdata = $this->user->insertdata($data);

                if ($insertdata) {
                    $this->response([
                        'status' => false,
                        'message' => 'Success'
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Failed to register'
                    ], 404);
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Something wrong'
            ], 404);
        }
    }

    public function fetch_get()
    {
        $id = $this->get('id');

        if ($id === NULL) {
            $user = $this->user->login();
        } else {
            $user = $this->user->login($id);
        }

        if ($user) {
            $this->response([
                'status' => true,
                'data' => $user
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No users were found'
            ], 404);
        }
    }

    public function logout()
    {
    }
}
