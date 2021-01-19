<?php



function is_login()
{
    $CI = get_instance();

    $roles = $CI->session->userdata('roles');


    if ($roles == 1) {
        redirect('admin/dashboard');
    } else if ($roles == 2) {
        redirect('user');
    }
}

function is_not_login()
{

    $CI = get_instance();
    $user = $CI->session->userdata('email');

    if (!$user) {
        redirect('auth');
    }
}

function user_menu()
{
    $ci = get_instance();
    $user_session = $ci->session->userdata('email');
}
