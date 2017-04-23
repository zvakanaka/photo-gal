<nav>
    <?php $action = (isset($action)) ? $action : filter_input(INPUT_GET, 'action'); ?>
      <img class="logo" src="<?php echo $project_dir.'/img/photo-gal.jpg'?>">
      <!-- <a class="logo" href="/">howtoterminal<span class="blue">$</span><span class="lime">&gt;</span><span class="blink">_</span></a> -->
      <?php
      $menu = array("Albums" => "home");

      if (isset($_SESSION["is_admin"])) {
        $menu["Users"] = "users";
        // $menu["DSLR"] = "dslr";
        $menu["DSLR"] = "download_upload";
      }
      if (isset($_SESSION["logged_in"])) {
        $menu["Faves"] = "review_favorites&user_id=".$_SESSION['user_id'];
        $menu["Log Out"] = "logout";
      } else {
        $menu["Login"] = "register";
      }
      ?>
        <?php
        foreach(array_keys($menu) as $key){
          echo '<a class="nav-link nav-a';
          if ( ''.$action == strip_extra_params($menu[$key]))
           echo " on";
           echo '" href="'.$project_dir."/?action=".$menu[$key].'" id="'.$menu[$key].'_tab">'.$key.'</a>';
        }
        ?>
</nav>
