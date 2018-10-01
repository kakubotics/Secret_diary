<?php        
        $link = mysqli_connect("hosting_name or ip","database_name","database_password","database_name");
        
        if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }
?>
