<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>

<main>
  <h1><?php echo ucfirst($album);?></h1>
<?php foreach ($images as $image) { ?>
    <a class="thumb-link" id="thumb-<?php echo strip_ext($image).".".$supported_format;?>" href="javascript:void(0)"
        onClick="getAndShow('<?php echo "$photo_dir/$album/.web/".strip_ext($image).".".$supported_format; ?>',
                            '<?php echo "$photo_dir/$album/.thumb/".strip_ext($image).".".$supported_format; ?>',
                            '<?php echo "$photo_dir/$album/$image"; ?>',
                            '<?php echo $album; ?>')">
      <img class="thumb" src="<?php echo "$photo_dir/$album/.thumb/".strip_ext($image).".".$supported_format;?>" alt="<?php echo "Thumbnail of ".$image;?>"/>
    </a>
<?php } ?>

</main>

<?php if (isset($_SESSION['is_admin'])) {
  if ($show_hidden == NULL) { ?>
    <div class="singularity">
      <a href="?action=album&album=<?php echo $album;?>&hidden=true">Show Hidden Photos</a>
    </div>
<?php } else { ?>
  <div class="singularity">
    <a href="?action=album&album=<?php echo $album;?>">Hide Hidden Photos</a>
  </div>

<?php } ?>
<?php if (!isset($message) && isset($album)) { ?>
  <form class="" action="." method="post">
    <input type="hidden" name="action" value="hide_album" />
    <input type="hidden" name="album_name" value='<?php echo "$album";?>'>
    <input type="submit" name="submit" value="Hide this album">
  </form>
<?php } else if (isset($message) && isset($album)) { ?>
  <form class="" action="." method="post">
    <input type="hidden" name="action" value="unhide_album" />
    <input type="hidden" name="album_name" value='<?php echo "$album";?>'>
    <input type="submit" name="submit" value="Unhide this album">
  </form>
<?php } ?>
<?php } ?>

<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/lightbox.php'; ?>
<!-- if query string photo, open lightbox for it -->
<?php if ($qs_photo !== NULL) { ?>
  <script type="text/javascript">
  var thumbToClick = document.getElementById('<?php echo "thumb-".$qs_photo;?>');
  thumbToClick.click();
  </script>
  <?php } ?>
<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
