<?php

// user role >>>
// function getUserRole()
// {
//     if (!isset($_SESSION['user']['status'])) return false;

//     if ($_SESSION['user']['status'] == 0) {
//         return "administrator";
//     }

//     if ($_SESSION['user']['status'] == 1) {
//         return "content_manager";
//     }
//     if ($_SESSION['user']['status'] == 2) {
//         return "user";
//     }
// }
// user role <<<

if(getUserRole() != 'administrator' && getUserRole() != 'content_manager') {
    header("location: ../");
    exit();
}
