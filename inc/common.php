<?php

   function start_session($name){
        session_name($name);
        session_start();
        setcookie(session_name(),session_id());
   }
   
   function check_log_in($session_name){
        start_session($session_name);
         if(isset($_SESSION['signed_in']) && ($_SESSION['signed_in'] == 1)) {
             return TRUE;
        } else {
            return FALSE;
        }
   }
?>