<?

setcookie("pass","",time()-60000);
setcookie("id","",time()-60000);
setcookie("type","",time()-60000);

session_start();
session_unset();
session_destroy();

header('Location: index.php');
exit;

?>
