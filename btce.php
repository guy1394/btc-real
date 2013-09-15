<?php

include_once('request.php');

class btce
{
  function tryLogin()
  {
    $request = new request('https://btc-e.com/ajax/login.php');
    $arg = array("email" => $email, "password" => $password, "otp" => "-", "PoW_nonce" => "");
    $res = $request->Post($arg)->Result();
    
  }
  function userLogin(a)
  {
    a.success?
    a.data.PoW&&$("#PoW_working").fadeTo(0,0.1,function(){setTimeout(function(){$("#PoW_nonce").val(getPoW(a.data.work.target,a.data.work.data));$("#PoW_working").hide()},30)}),setTimeout(function(){a.data.otp?($("#login_con").hide(),$("#otp_con").fadeIn(100),$("#login-otp").val("").focus()):(a.data.login_success&&(success_login=!0,$("#login").attr("action",window.location.href)),$("#login").submit())},100)):(alert(a.error),"-"!=$("#login-otp").val()&&$("#login-otp").val(""));
$("#PoW_nonce").val("")}
}
  function getPoW(a,b){var c=0;do hash_hex=md5(md5(b+c)),hash=eval("(0x"+hash_hex+")"),++c;while(hash>=a);return c}function comm_replace(a){~a.value.indexOf(",")&&(a.value=a.value.replace(",","."))}function ac_add_comm_butt(){$("#add_comm_butt").hide();$("#add_comm_con").fadeIn(300)}function ac_add_comment(){var a=$("#profile_uid").val();ac_add_comment_request(a,$("#comment_text").val())}
