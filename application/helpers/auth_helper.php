<?php



function is_login()
{
    $CI = get_instance();

    $roles = $CI->session->userdata('roles');
    $email = $CI->session->userdata('email');

    if (!$CI->session->userdata('email')) {
        redirect('auth');
    } else {
        $menu = $CI->uri->segment(1);

        $queryMenu = $CI->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        $userAccess = $CI->db->get_where('user_group_menu', [
            'roles_id' => $roles,
            'menu_id' => $menu_id
        ]);
    }
    // if ($roles == 1) {
    //     redirect('admin/dashboard');
    // } else if ($roles == 2) {
    //     redirect('user');
    // }

    if ($userAccess->num_rows() < 1) {
        redirect('auth/blocked');
    }
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('roles_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('user_group_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function is_not_login()
{

    $CI = get_instance();
    $roles = $CI->session->userdata('roles');

    if ($roles) {
        if ($roles == 1) {
            redirect('admin/dashboard');
        } else if ($roles == 2) {
            redirect('user/dashboard');
        }
    }
}

function user_menu()
{
    $ci = get_instance();
    $user_session = $ci->session->userdata('email');
    $roles = $ci->session->userdata('roles');

    $ci->load->model('Menu_model', 'menu');

    return $ci->menu->get_menu($roles);
}

function user_sub_menu($menu_id)
{

    $ci = get_instance();
}
