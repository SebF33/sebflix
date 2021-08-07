<?php
require "src/validation/validation.php";
require "src/datamanager/user-manager.php";

session_start();

function validatePwd($pwd)
{
  return htmlspecialchars(trim($pwd), ENT_QUOTES);
}

$pseudo = $_SESSION['pseudo'];
$actualPwd = validatePwd($_POST['actual-password']);
$newPwd = validatePwd($_POST['new-password']);
$confirmPwd = validatePwd($_POST['confirm-password']);

if (in_array("", $_POST)) {
  $msg_error = "Please fill all required fields.";
} else {
  $userdb = select_user($pseudo);

  if (password_verify($actualPwd, $userdb[0]['password'])) {
    if ($newPwd === $confirmPwd) {
      if (strlen($newPwd) > 8) {
        $newPwd = password_hash($newPwd, PASSWORD_DEFAULT);
        $updated = update_pwd($pseudo, $newPwd);

        if ($updated) {
          $set_request = TRUE;
        } else {
          $msg_error = "An error occured, the password was not modified.";
        }
      } else {
        $msg_error = "The password should be at least 8 characters long";
      }
    } else {
      $msg_error = "The new password and its confirmation do not match.";
    }
  } else {
    $msg_error = "Your actual password is wrong.";
  }
}

$last_url = $_SERVER['HTTP_REFERER'];
if (strpos($last_url, '?') !== FALSE) {
  $req_get   = strrchr($last_url, '?');
  $last_url   = str_replace($req_get, '', $last_url);
}
if (isset($msg_error)) {
  header("Location: $last_url?msg_err=$msg_error");
} elseif ($set_request) {
  $msg_success = "The password has been modified successfully";
  header("Location: $last_url?msg_success=$msg_success");
}
