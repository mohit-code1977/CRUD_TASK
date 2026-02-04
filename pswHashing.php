<?php
  $plaintext_password = "Password@123";

  $hash = password_hash($plaintext_password, PASSWORD_DEFAULT);
// $hash = base64_encode($plaintext_password);
  echo $hash;

  $verify = password_verify("Password@122", $hash);

  print("\n");

  if($verify){
    echo "Password Verify";
  }
else{
    echo "Password is not Verify";
}

print("\n");

$decond_psw = base64_decode($hash);
echo $decond_psw;
?>