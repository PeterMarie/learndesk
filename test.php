<?php
    
    //require_once('backend.php');
    //echo password_hash("unshakes", PASSWORD_DEFAULT);
    //$value = strlen(password_hash("askf", PASSWORD_DEFAULT));
    if(isset($_GET)){
        echo strtotime($_GET['due_date']);
    }
?>
<form>
          <div>
            <label for="due_date">Set Due Date</label>
            <input type="date" name="due_date" id="due_date" />
          </div>
          <input type="submit" value="value"  />
</form>