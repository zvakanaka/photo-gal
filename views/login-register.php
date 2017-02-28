<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
    <main>
    <h1>Login</h1>
    <form action="." method="post" id="login_form">
        <input type="hidden" name="action" value="authenticate">

        <label>Username:</label>
        <input required type="text" name="username"<?php if(isset($username)) echo " value='$username'";?>/>
        <br>

        <label>Password:</label>
        <input required type="password" name="password"<?php if(isset($password)) echo " value='$password'";?>/>
        <br>

        <label>&nbsp;</label>
        <input type="submit" value="Login" />
        <br>
    </form>
    <h1>Register</h1>
    <form action="." method="post" id="registration_form">
        <input type="hidden" name="action" value="insert_user">

        <label>First Name:</label>
        <input required type="text" name="firstname"<?php if(isset($firstname)) echo " value='$firstname'";?>/>
        <br>

        <label>Last Name:</label>
        <input required type="text" name="lastname"<?php if(isset($lastname)) echo " value='$lastname'";?>/>
        <br>

        <label>Username:</label>
        <input required type="text" name="new-username"<?php if(isset($username)) echo " value='$username'";?>/>
        <br>

        <label>Password:</label>
        <input required type="password" name="new-password"/>
        <br>
        <label>Confirm Password:</label>
        <input required type="password" name="confirm-new-password"/>
        <br>

        <label>Email:</label>
        <input required type="email" name="email"<?php if(isset($email)) echo " value='$email'";?>/>
        <br>

        <label>&nbsp;</label>
        <input type="submit" value="Register" />
        <br>
    </form>
  </main>

<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
