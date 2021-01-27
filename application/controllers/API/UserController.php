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
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!empty($email) && !empty($password)) {
            $user = $this->user->check_user($email);
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $session = [
                        'is_login' => 'true',
                        'first_name' => $user['first_name'],
                        'email' => $user['email'],
                        'roles' => $user['roles']
                    ];
                    $this->session->set_userdata($session);

                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => $session
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Wrong Password'
                    ], 404);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Provide email and password'
            ], 404);
        }
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

        $token = base64_encode(random_bytes(32));

        $emailConfig = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'indradullanov1@gmail.com',
            'smtp_pass' => 'emansudirman123',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)) {
            // $data['email'] = $email;
            $user_check = $this->user->check_user($email);

            if ($user_check > 0) {
                $this->response([
                    'status' => false,
                    'message' => 'Email already registered, check your email for verification'
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
                    'deleted_at' => '',
                    'last_login' => ''
                ];

                $insertdata = $this->user->insertdata($data);

                if ($insertdata) {

                    $this->email->initialize($emailConfig);
                    $this->email->from('indradullanov1@gmail.com', 'User Activation');
                    $this->email->to($email);
                    $this->email->subject('Account Verification');
                    $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $email . '&token=' . urlencode($token) . '">Activate</a>');
                    if ($this->email->send()) {
                        $this->response([
                            'status' => true,
                            'message' => 'Success registered, please check email for verification your account'
                        ], 200);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Failed to register'
                        ], 404);
                    }
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
