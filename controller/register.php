<?php
!defined('IN_PTF') && exit('ILLEGAL EXECUTION');
/**
 * @author  ryan <cumt.xiaochi@gmail.com>
 */

if ($has_login)
    redirect();

list(
    $username, 
    $password, 
    $repassword
) = _post(
    'username',
    'password',
    'repassword'
);

$msg = '';

if ($by_post) {
    $ERROR_INFO = $config['error']['info'];
    if (empty($password)) {
        $msg = $ERROR_INFO['PASSWORD_EMPTY'];
    } elseif (empty($repassword)) {
        $msg = $ERROR_INFO['REPASSWORD_EMPTY'];
    } elseif ($password !== $repassword) {
        $msg = $ERROR_INFO['PASSWORD_NOT_SAME'];
    } elseif (empty($username)) {
        $msg = $ERROR_INFO['USERNAME_EMPTY'];
    } elseif (User::find($username)) {
        $msg = $ERROR_INFO['USER_ALREADY_EXISTS'];
    } else {
        $user = User::register($username, $password);
        $user->login();
        $back_url = _req('back') ?: DEFAULT_LOGIN_REDIRECT_URL;
        redirect($back_url);
    }
}

$view .= '?master';
