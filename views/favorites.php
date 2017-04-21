<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
<main>
    <h1>Manage Favorites for <a href="mailto:<?php echo get_user_email($user_id);?>"><?php echo get_user_name($user_id);?></a></h1>
    <table>
        <tr>
          <th>Image</th>
          <th>Album</th>
          <th>Name</th>
          <th>&nbsp;</th>
        </tr>
        <?php foreach ($faves as $fave) : ?>
            <tr>
              <td> <a href=<?php echo "'.?action=album&album=".$fave['album_name']."&photo=".strip_ext($fave['photo_name'])."$supported_format'";?>><img src=<?php echo "'../../photo/".$fave['album_name']."/.thumb/".strip_ext($fave['photo_name']).".$supported_format'";?> alt="Favorite image: <?php echo $fave['photo_name']." from the album: ".$fave['album_name'];?>" /></a></td>
              <td> <?php echo strip_ext($fave['album_name']);?></td>
              <td> <?php echo strip_ext($fave['photo_name']);?></td>
              <td><form action="." method="post">
                <input type="hidden" name="action" value="delete_favorite">
                <input type="hidden" name="photo_name"
                       value="<?php echo $fave['photo_name']; ?>">
                 <input type="hidden" name="user_id_to_unfavorite"
                        value="<?php echo $fave['user_id']; error_log($fave['user_id']);?>">
               <input type="hidden" name="album_name"
                      value="<?php echo $fave['album_name']; ?>">
                <input type="submit" value="Unfavorite">
            </form></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php if (isset($_SESSION['is_admin'])) { ?>
  <div class="singularity">
    <h1>Zip Favorites</h1>
    <a href="?action=zip_favorites&user_id=<?php echo $user_id;?>">Create Zip</a>
  </div>
  <?php if (filter_input(INPUT_GET, 'zip') != NULL) { ?>
      <div class="singularity">
        <h1>Download Favorites</h1>
        <p>You may download <a href="../../zips/<?php echo filter_input(INPUT_GET, 'zip');?>"><?php echo filter_input(INPUT_GET, 'zip');?></a> when it is ready.</p>
      </div>
  <?php } ?>
<?php } ?>
<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
