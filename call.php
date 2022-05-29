<?php
require("./functions.php");
if (isset($_POST['metamask'])) {
    if (isset($_COOKIE['userid'])){
        $exist = selectOne('accounts', ['id' => $_COOKIE['userid']]);
        if ($exist['metamask'] !== NULL){
            echo "wallet has been connected";
        }else{
            $update = update('accounts',$_COOKIE['userid'], ['metamask'=>$_POST['metamask']]);
            if ($update){
                setcookie('userid', $update['id'], time() + (86400 * 30), '/');
                echo 'metamask wallet has been connected successfully';
            }
        }
    }else{
        $exist = selectOne('accounts', ['metamask' => $_POST['metamask']]);
    if ($exist) {
        setcookie('userid', $exist['id'], time() + (86400 * 30), '/');
        echo "your data has been retrieved from last session";
    } else {
        $update = create('accounts', ['metamask' => $_POST['metamask']]);
        setcookie('userid', $update['id'], time() + (86400 * 30), '/');
        if ($update) {
            echo "metamask wallet connected succesfully";
        }
    }
    }
} else if (isset($_POST['phantom'])) {
    

    if (isset($_COOKIE['userid'])){
        $exist = selectOne('accounts', ['id' => $_COOKIE['userid']]);
        if ($exist['phantom_wallet'] !== NULL){
            echo "wallet has been connected";
        }else{
            $update = update('accounts',$_COOKIE['userid'], ['phantom_wallet'=>$_POST['phantom']]);
            if ($update){
                echo 'phantom wallet has been connected successfully';
            }
        }
    }else{
        $exist = selectOne('accounts', ['phantom_wallet' => $_POST['phantom']]);
    if ($exist) {
        setcookie('userid', $exist['id'], time() + (86400 * 30), '/');
        echo "your data has been retrieved from last session";
    } else {
        $update = create('accounts', ['phantom_wallet' => $_POST['phantom']]);
        if ($update) {
            echo "Phantom wallet connected succesfully";
        }
    }
    }
} else if (isset($_POST['email'])) {
    if (isset($_COOKIE['userid'])){
        $exist = selectOne('accounts', ['id' => $_COOKIE['userid']]);
        $update = update('accounts', $exist['id'], ['email' => $_POST['email']]);
        if ($update) {
           echo "email has been added successfully. check your mail to verify";
        }
    }else{
        echo "error";
    }
}else if(isset($_POST['delete_metamsk'])){
    $delete = update('accounts',$_COOKIE['userid'], ['metamask'=>NULL]);
    if ($delete){
        echo 'metamask wallet deleted successfully';
    }else{
        echo 'unable to delete wallet.';
    }
}else if(isset($_POST['delete_phantom'])){
    $delete = update('accounts',$_COOKIE['userid'], ['phantom_wallet'=>NULL]);
    if ($delete){
        echo 'phantom wallet deleted successfully';
    }else{
        echo 'unable to delete wallet.';
    }
}else if(isset($_POST['delete_email'])){
    $delete = update('accounts',$_COOKIE['userid'], ['email'=>NULL]);
    if ($delete){
        echo 'email deleted successfully';
    }else{
        echo 'unable to delete email.';
    }
}else if(isset($_POST['accountDataMetamask'])){
    if (isset($_COOKIE['userid'])){
        $select = selectOne('accounts',['id'=>$_COOKIE['userid']]);
        
        if ($select['metamask'] !== NULL){
            echo $select['metamask'];
        }
    }
}
else if(isset($_POST['accountDataPhantom'])){
    if (isset($_COOKIE['userid'])){
        $select = selectOne('accounts',['id'=>$_COOKIE['userid']]);
        
        if ($select['phantom_wallet'] !== NULL){
            echo $select['phantom_wallet'];
        }
    }
}