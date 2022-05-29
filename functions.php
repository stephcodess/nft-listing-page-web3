<?php
include "./database.php";
global $conn;

session_start();

function dd($values){
  echo "<pre>".print_r($values,true)."</pre>";
  die();
}

    function executeQuery($sql,$data){
    global $conn;
    $stmt=$conn->prepare($sql);
    $values=array_values($data);
    $types=str_repeat('s',count($values));
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    return $stmt;
    }
    function selectAll($table,$conditions=[]){
     global $conn;
     $sql="SELECT * FROM $table";
     if(empty($conditions)){
         $stmt=$conn->prepare($sql);
         $stmt->execute();
           $records = $stmt->get_result();
           $records->fetch_all(MYSQLI_ASSOC);
          return $records;
     }
     else{

         $i=0;

         foreach ($conditions as $key => $value) {
             if($i===0){
                 $sql=$sql. " WHERE $key=?";
             }else{
                  $sql=$sql. " AND $key=?";
              }$i++;
          }
          $stmt=executeQuery($sql,$conditions);
        //   $stmt=$conn->prepare($sql);
          $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
          return $records;
      }
  }

  function selectOne($table,$conditions){
      global $conn;
      $sql="SELECT * FROM $table";

      $i=0;

      foreach ($conditions as $key => $value) {
          if($i===0){
              $sql=$sql. " WHERE $key=?";
          }else{
              $sql=$sql. " AND $key=?";
          }$i++;
      }
      $sql=$sql . " LIMIT 1";
      $stmt=executeQuery($sql,$conditions);
      $records = $stmt->get_result()->fetch_assoc();
      return $records;
  }

  function create($table, $data)
  {
      global $conn;
      $sql="INSERT INTO $table SET ";

      $i=0;

      foreach ($data as $key => $value) {
          if($i===0){
              $sql=$sql . " $key=?";
          }else{
              $sql=$sql . ", $key=?";
          }$i++;
      }
      $stmt=executeQuery($sql, $data);
      $id=$stmt->insert_id;
      return $id;
  }
// $data = [
//       'position' => 0,
//       'name' => 'Boruto ',
//       'password' =>'steph'
//   ];
//   $user=create('users', $data);
//   dd($user);

  function deleted($table,$id)
  {
      global $conn;
      $sql="DELETE FROM $table WHERE email=?";

      $stmt=executeQuery($sql, ['email'=>$id]);
      return $stmt->affected_rows;
  }
  function update($table,$id,$data)
  {
      global $conn;
      $sql="UPDATE $table SET ";

      $i=0;

      foreach ($data as $key => $value) {
          if ($i===0){
              $sql=$sql . " $key=?";
          }else{
              $sql=$sql . ", $key=?";
          }$i++;
      }

      $sql= $sql. " WHERE id=?";
      $data['id']= $id;
      $stmt=executeQuery($sql,$data);
      return $stmt->affected_rows;
  }

// function searchPosts($term){
//     $match = '%' . $term . '%' ;
//     global $conn;
//     $sql="SELECT * FROM products WHERE publish=? AND name like ? OR category LIKE ? LIMIT 10";

//     $stmt= executeQuery($sql, ['publish' => 1,'name'=>$match, 'category'=>$match]);
//     $records=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
//     return $records;

//   }


  function logged_in(){
   if (isset($_COOKIE['DEMIWEARS'])){
       $loggedInUser=selectOne('login_tokens',['tokens'=>sha1($_COOKIE['DEMIWEARS'])]);
       if($loggedInUser['user_id']){
           if (isset($_COOKIE['DEMIWEARS_'])){
               return $loggedInUser['user_id'];
           }else{
            $user=selectOne('register',['id'=>$loggedInUser['user_id']]);
            $true=true;
            $token=bin2hex(openssl_random_pseudo_bytes(64,$true));
            $create_token=create('login_tokens',['tokens'=>sha1($token),'user_id'=>$user['id']]);
            // $delete=delete('login_tokens',$loggedInUser['id']);
            setcookie('DEMIWEARS',$token,time()+60*60*24*7,'/',NULL,NULL,TRUE);
            setcookie('DEMIWEARS_','1',time()+60*60*24*3);
            return $create_token['user_id'];
           }
       }
   }
   return $loggedInUser['user_id'];
}
?>