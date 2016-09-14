<?php
  if( !defined('APP_VERSION') ) exit('You can\'t access this file');

  $out = '';
  $out .= '<form id="login-form" name="login-form" class="login-form col-md-6" method="post" action="#">';
    $out .= '<h2>Sign In</h2>';
    $out .= '<table>';
      $out .= '<tr>';
        $out .= '<td>';
          $out .= '<label for="login_user_name">Login: </label>';
        $out .= '</td>';
        $out .= '<td>';
          $out .= '<input type="text" id="login_user_name" name="user_name">';
        $out .= '</td>';
      $out .= '</tr>';
      $out .= '<tr>';
        $out .= '<td>';
          $out .= '<label for="login_user_pass">Password: </label>';
        $out .= '</td>';
        $out .= '<td>';
          $out .= '<input type="text" id="login_user_pass" name="user_pass">';
        $out .= '</td>';
      $out .= '</tr>';
      $out .= '<tr>';
        $out .= '<td>';
        $out .= '</td>';
        $out .= '<td>';
          $out .= '<input type="submit" name="submit" value="Login">';
        $out .= '</td>';
      $out .= '</tr>';
    $out .= '</table>';
  $out .= '</form>';
  return $out;
?>