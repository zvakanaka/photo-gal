<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
    <main>
    <h1>Login</h1>
    <div class="center wrap" id="login">
      <form action="." method="post" id="login_form">
          <input type="hidden" name="action" value="authenticate">

          <label>Username
            <input required type="text" name="username"<?php if(isset($username)) echo " value='$username'";?>/>
          </label>

          <label>Password
            <input required type="password" name="password"<?php if(isset($password)) echo " value='$password'";?>/>
          </label>

          <label>&nbsp;
            <input type="submit" value="Login" />
          </label>
      </form>
    </div>
    <h1>Register</h1>
    <div class="center wrap" id="register">
      <form action="." method="post" id="registration_form">
          <input type="hidden" name="action" value="insert_user">

          <!-- <label>First Name
            <input required type="text" name="firstname"<?php //if(isset($firstname)) echo " value='$firstname'";?>/>
          </label>
          <label>Last Name
            <input required type="text" name="lastname"<?php //if(isset($lastname)) echo " value='$lastname'";?>/>
          </label> -->
          <label>Username
            <input required type="text" name="new-username"<?php if(isset($username)) echo " value='$username'";?>/>
          </label>

          <label>Password
            <input required type="password" name="new-password"/>
          </label>
          <label>Confirm Password
            <input required type="password" name="confirm-new-password"/>
          </label>

          <label>Email
            <input required type="email" name="email"<?php if(isset($email)) echo " value='$email'";?>/>
          </label>

          <label>&nbsp;
            <input type="submit" value="Register" />
          </label>
      </form>
    </div>
  </main>

<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
