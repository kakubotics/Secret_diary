<?php

    session_start();

    $error = "";    

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  

        session_destroy();
        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        include("connection.php");
        
        
        if (($_POST["password"] == "") and ($_POST["email"] == "")) {
            
            $error = '<div class="alert alert-danger" role="alert">Email address and password is required</div>';
            
        }

        else if (!$_POST['email']) {
            
            $error .= '<div class="alert alert-danger" role="alert">An email address is required</div>';
            
        } 
        
        else if (!$_POST['password']) {
            
            $error .= '<div class="alert alert-danger" role="alert">A password is required</div>';
            
        } 
        
         else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = '<div class="alert alert-danger" role="alert">That email address is already taken.</div>';

                } else {

                    $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = '<div class="alert alert-danger" role="alert">Could not sign you up - please try again later.</div>';

                    } else {

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        

                        
                        
                        $id = mysqli_insert_id($link);

                        mysqli_query($link, $query);

                        $_SESSION['id'] = $id;

                        if (isset($_POST['stayLoggedIn'])) {



                            setcookie("id", $id, time() + 60*60*24*365);

                        } 



                        header('Location: loggedinpage.php');

                    }

                } 
                
            } else {
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['id'] = $row['id'];
                            
                            if (isset($_POST['stayLoggedIn']) ) {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            } 

                            header("Location: loggedinpage.php");
                                
                        } else {
                            
                            $error = '<div class="alert alert-danger" role="alert"><p><strong>That email/password combination could not be found.</strong></div>';
                            
                        }
                        
                    } else {
                        
                        $error = '<div class="alert alert-danger" role="alert"><p><strong>That email/password combination could not be found.</strong></div>';
                        
                    }
                    
                }
            
        }
        
        
    }


?>

	
	<?php include("header.php"); ?>
	

	
  	<div class="container" id="homePageContainer">
      

    <h1><span class="title2">Secret </span><i class="fas fa-user-secret"></i><span class="title3"> Diary</span></h1>

    <div><strong><span class="title1">Store your thoughts permanently and securely.</span></strong></div>
    

    <div id="error"><?php echo $error; ?></div>

	<form method="post" id="signUpForm">

      <p><b>Interested? Sign Up now.</b></p>

	    
	    <div class="form-group">
		    <label for="exampleInputEmail1"></label>
		    <input type="email" class="form-control"  aria-describedby="emailHelp" name="email" placeholder="Enter email">
		    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
		 </div>
	    
	    
	    <div class="form-group">
		    <label for="exampleInputPassword1"></label>
		    <input type="password" class="form-control" name="password" placeholder="Password">
		</div>
	    
	    
	    <div class="form-group form-check">
		    <input type="checkbox" class="form-check-input" name="stayLoggedIn" value=1>
		    <label class="form-check-label" for="exampleCheck1">Stay Logged In</label>
		</div>
	    
	    

	    <div class="form-group">
			 <input type="hidden" name="signUp" value="1">
	         <input type="submit" name="submit" value="Sign Up!" class="btn btn-success">
		</div>

		<p class="bottom"><a class="toggleForms" href="#">Log In</a></p>

	</form>

	
	

	<form method="post" id="logInForm">

      <p><b>Log in using your email and password.</b></p>

	    
	    <div class="form-group">
		    <label for="exampleInputEmail1"></label>
		    <input type="email" class="form-control"  aria-describedby="emailHelp" name="email" placeholder="Enter email">
		    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
		 </div>
	    
	    
	    <div class="form-group">
		    <label for="exampleInputPassword1"></label>
		    <input type="password" class="form-control" name="password" placeholder="Password">
		</div>
	    
	    
	    <div class="form-group form-check">
		    <input type="checkbox" class="form-check-input" name="stayLoggedIn" value=1>
		    <label class="form-check-label" for="exampleCheck1">Stay Logged In</label>
		</div>
	    
	  

	    <div class="form-group">
			 <input type="hidden" name="signUp" value="0">
	         <input type="submit" name="submit" value="Log In" class="btn btn-success">
		</div>

		<p class="bottom"><a class="toggleForms" href="#">Sign up</a></p>

	</form>
    
    
      <div>
      
   	  <a href='connect.php'><button type="button" class="btn btn-primary" id="ask">For any query? Ask here.</button></a>
      
      <div id="maker">Handcrafted by:Kakubotics</div>
      </div>  
	  


    </div>
	 
    

	<?php include("footer.php"); ?>