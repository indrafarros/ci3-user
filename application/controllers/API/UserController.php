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
        $data = $this->user->contoh();
        if ($data) {
            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => $data
            ], 200);
        }
    }

    public function login_post()
    {
        $config = [
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|trim|valid_email',
                'errors' => [
                    'required' => 'You must fill the field',
                    'valid_email' => 'This email is not valid'
                ],

            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],

        ];

        $data = array(
            'email' => $this->post('email'),
            'password' => $this->post('password')
        );

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'message' => $this->form_validation->error_array()
            ], 404);
        } else {
            $validate = $this->user->login($data);
            if ($validate) {
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $validate
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        }
    }

    public function register_post()
    {

        $config = [
            [
                'field' => 'first_name',
                'label' => 'first_name',
                'rules' => 'required|alpha_dash',
                'errors' => [
                    'required' => 'This field cannot be null',
                    'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input',
                ],
            ],
            [
                'field' => 'last_name',
                'label' => 'last_name',
                'rules' => 'required|alpha_dash',
                'errors' => [
                    'required' => 'This field cannot be null',
                    'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input',
                ],
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|trim|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'We need both username and password',
                    'is_unique' => 'This email has already registered!',
                ],
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
            [
                'field' => 'phone',
                'label' => 'phone',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'numeric' => 'This field only accept numbers',
                ],
            ],
        ];


        $data = array(
            'first_name' => $this->post('first_name'),
            'last_name' => $this->post('last_name'),
            'email' => $this->post('email'),
            'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
            'phone' => $this->post('phone'),
            'create_at' => time(),
            'update_at' => time(),
            'roles' => '1'
        );

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'message' => $this->form_validation->error_array()
            ], 404);
        } else {
            $insert = $this->user->create_user($data);
            if ($insert) {
                $this->response([
                    'status' => true,
                    'message' => 'Success to create new user',
                    'data' => $this->db->insert_id()
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Something wrong'
                ], 404);
            }
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
