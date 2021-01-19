<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model', 'auth');
    }

    private function _configRules()
    {
        $config = [
            [
                'field' => 'first_name',
                'label' => 'first_name',
                'rules' => 'required|alpha_dash|trim',
                'errors' => [
                    'required' => 'This field cannot be null',
                    'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input'

                ],
            ],
            [
                'field' => 'last_name',
                'label' => 'last_name',
                'rules' => 'required|alpha_dash|trim',
                'errors' => [
                    'required' => 'This field cannot be null',
                    'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input',
                ],
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'This field cannot be null.'
                ],
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[6]|matches[password_confirmation]',
                'errors' => [
                    'matches' => 'Password dont match!',
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
            [
                'field' => 'password_confirmation',
                'label' => 'password_confirmation',
                'rules' => 'required|min_length[6]|matches[password]',
                'errors' => [
                    'matches' => 'Password dont match!',
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
            [
                'field' => 'phone',
                'label' => 'phone',
                'rules' => 'numeric',
                'errors' => [
                    'numeric' => 'This field only accept numbers',
                ],
            ],
            // $this->form_validation->set_rules('accept_terms','TOS','trim|required|xss_clean|greater_than[0]');

            [
                'field' => 'accept_terms',
                'label' => 'Terms',
                'rules' => 'trim|required|greater_than[0]',
                'errors' => [
                    'required' => 'You should accept terms'
                ]
            ]
        ];


        $rules =  $this->form_validation->set_rules($config);
        return $rules;
    }

    private function _sendEmail($token, $type)
    {
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
        $this->email->initialize($emailConfig);


        $this->email->from('indradullanov1@gmail.com', 'User Activation');
        $this->email->to($_POST['email']);

        if ($type == 'verify') {

            $this->email->subject('Account Verification');
            $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $_POST['email'] . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot_password') {
            $this->email->subject('Account Verification');
            $this->email->message('Click this link to reset you password account : <a href="' . base_url() . 'auth/reset_password?email=' . $_POST['email'] . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'new_token') {

            // $this->email->subject('Account Verification');
            // $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $_POST['email'] . '&token=' . urlencode($token) . '">Activate</a>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Something wrong! </div>');
        }


        // if ($this->email->send()) {
        //     return true;
        // } else {
        //     echo $this->email->print_debugger();
        //     die();
        // }

        // if ($type == 'verify') {
        //     $this->email->subject('Account Verification');
        //     $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        // } else if ($type == 'forgot') {
        //     $this->email->subject('Reset Password');
        //     $this->email->message('Click this link to reset your password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        // }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }


    public function index()
    {
        $this->login();
    }

    public function login()
    {
        // if ($this->session->userdata('roles') == 1) {
        //     redirect('admin/dashboard');
        // }
        is_login();

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Login';
            $this->load->view('templates/auth/header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth/footer');
        } else {
            $this->_hasLogin();
        }
    }

    private function _hasLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->auth->user($email);
        if ($user) {
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {
                    $session = [
                        'is_login' => 'true',
                        'first_name' => $user['first_name'],
                        'email' => $user['email'],
                        'roles' => $user['roles']
                    ];
                    $this->session->set_userdata($session);
                    if ($user['roles'] == 1) {
                        redirect('admin/dashboard');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Wrong password! </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email has not been activated! </div>');
                redirect('auth');
            }
        }
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
        Email has not registered! </div>');
        redirect('auth');
    }

    public function verify()
    {
        $email = $_GET['email'];
        $token = $_GET['token'];

        $user = $this->auth->get_activation_user($email, $token, 'email');

        if ($user) {
            $user_token = $this->auth->get_activation_user($email, $token, 'token');
            if ($user_token) {
                if (time() - $user_token['create_at'] < (60 * 60 * 6)) {
                    $this->auth->verify_email($email, $token, 'activation_success');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $email . ' has been activated! Please login.</div>');
                    redirect('auth');
                } else {
                    $new_token = base64_encode(random_bytes(32));

                    // $email = $_POST['email'];

                    // $this->auth->create_account_verification($data, 'new_token');
                    // $this->auth->verify_email($email, $token, 'activation_expired');
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Activation expired. <a href="' . base_url() . 'auth/newActivation?email=' . $email . '&token=' . urlencode($token) . '">Send new activation</a></div>');
                    $this->session->set_userdata('change_token', $email);
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Something wrong.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed! Account not found.</div>');
            redirect('auth');
        }
    }

    public function newActivation()
    {
        if (!$this->session->userdata('change_token')) {
            redirect('auth');
        }
        $email = $_GET['email'];
        $old_token = $_GET['token'];
        // var_dump($email, $old_token);
        // die();
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
        $check_token = $this->auth->get_activation_user($email, $old_token, 'token');
        if ($check_token) {
            $this->email->initialize($emailConfig);
            // $token = $_POST['token'];

            $data = array(
                'email' => $email,
                'new_token' =>  base64_encode(random_bytes(32))
            );
            $this->auth->create_account_verification($data, 'new_token');

            $this->email->from('indradullanov1@gmail.com', 'User Activation');
            $this->email->to($email);



            $this->email->subject('Account Verification');
            $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $email . '&token=' . urlencode($data['new_token']) . '">Activate</a>');

            if ($this->email->send()) {
                $this->session->unset_userdata('change_token');
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                New verication succesfully send, please check your email for verication! </div>');
                redirect('auth');
            } else {
                $this->session->unset_userdata('change_token');
                echo $this->email->print_debugger();
                die;
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            User not found! </div>');
            redirect('auth');
        }
    }

    public function register()
    {
        $this->_configRules();

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/register');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
                'is_unique' => 'This email has already registered!'
            ]);
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('auth/register');
            } else {
                $data = array(
                    'first_name' => html_escape($_POST['first_name']),
                    'last_name' => html_escape($_POST['last_name']),
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'phone' => '0',
                    'create_at' => time(),
                    'update_at' => time(),
                    'is_active' => 0,
                    'roles' => '1'
                );
                $this->form_validation->set_data($data);

                $token = base64_encode(random_bytes(32));

                $data_token = array(
                    'email' => $_POST['email'],
                    'token' => $token,
                    'create_at' => time(),
                    'deleted_at' => ''
                );

                $insert = $this->auth->register($data);
                $this->auth->create_account_verification($data_token, 'register');

                if ($insert) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Registration successful, please check your email for verication! </div>');
                    $this->_sendEmail($token, 'verify');
                    redirect('auth');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Something wrong, please try again! </div>');
                    // redirect('auth');
                }
            }
        }
    }


    public function logout()
    {
        $session = [
            'is_login',
            'first_name',
            'email',
            'roles'
        ];

        $this->session->unset_userdata($session);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           You have been logout!
          </div>');
        redirect('auth');
    }

    public function forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
        // $email = $_POST['email'];

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('auth/forgot_password');
        } else {
            $email = $_POST['email'];

            $user_check = $this->auth->user($email);

            if ($user_check['is_active'] == 1) {
                $token = base64_encode(random_bytes(32));

                $data_token = array(
                    'email' => $_POST['email'],
                    'token' => $token,
                    'create_at' => time(),
                    'deleted_at' => ''
                );

                $insert =  $this->auth->create_account_verification($data_token, 'register');
                if ($insert) {
                    $this->_sendEmail($token, 'forgot_password');
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check your email to reset your password!</div>');
                    redirect('auth/forgot_password');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Something wrong, please try again!</div>');
                    redirect('auth/forgot_password');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered or activated!</div>');
                redirect('auth/forgot_password');
            }
        }
    }

    public function reset_password()
    {
        $email = $_GET['email'];
        $token = $_GET['token'];

        $check_user = $this->auth->user($email);
        if ($check_user) {
            $check_token = $this->auth->get_activation_user($email, $token, 'token');
            if ($check_token) {
                $this->session->set_userdata('new_reset', $email);
                $this->change_password();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Something wrong.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! email not found.</div>');
            redirect('auth');
        }
    }

    public function change_password()
    {

        if (!$this->session->userdata('new_reset')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|matches[password_confirmation]');
        $this->form_validation->set_rules('password_confirmation', 'Repeat Password', 'trim|required|min_length[3]|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/reset_password');
        } else {
            $data = array(
                $email = $this->session->userdata('new_reset'),
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT)
            );

            $this->auth->change_password($data);

            $this->session->unset_userdata('new_reset');
        }
    }
}
