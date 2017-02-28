<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
<main>
  <div class="singularity">
    <p class="message">
      ATTENTION: DO NOT SUBMIT ANY OF THESE FORMS NO MATTER WHO YOU ARE.
    </p>
    <p>
      They are not for testing purposes, they are for adding batches of photos from a DSLR plugged into the server, and for adding thumbnails and web-optimized previews. All of this has been done already and repeating may be destructive.
    </p>
</div>
<h1>Server Management</h1>
  <h2>Download from DSLR</h2>
  <form action="." method="post" id="download_form">
      <input type="hidden" name="action" value="download_from_dslr">

      <label>New Album Name (a directory name):</label>
      <input type="text" name="new_album"<?php if(isset($new_album)) echo " value='$new_album'";?>/>
      <br>

      <label>&nbsp;</label>
      <input type="submit" value="Download" />
      <br>
  </form>
  <h2>Image Optimizations</h2>
      <form action="." method="post" id="thumb_form">
          <input type="hidden" name="action" value="optimize">

          <label>Optimization:</label>
          <br>
          <input type="radio" name="optimization_type" value="thumbs">Thumbnails<br>
          <input type="radio" name="optimization_type" value="webs">Webs<br>
          <input type="radio" name="optimization_type" value="delete_originals">Delete Originals<br>

          <label>Album:</label>
          <select name="album_name">
            <option disabled selected value> -- select an album -- </option>
            <?php foreach ($albums as $album) { ?>
                <option value="<?php echo $album;?>">
                  <?php echo $album;?>
                </option>
            <?php } ?>
          </select>
          <br>

          <label>&nbsp;</label>
          <input type="submit" value="Optimize Images" />
          <br>
      </form>

    <h2>Upload to Server</h2>
    <form action="." method="post" id="upload_form">
        <input type="hidden" name="action" value="upload_to_server">

        <label>Server Name (IP address or Domain Name):</label>
        <input type="text" name="server_name"<?php if(isset($server_name)) echo " value='$server_name'";?>/>
        <br>

        <label>User Name (On server):</label>
        <input type="text" name="username"<?php if(isset($username)) echo " value='$username'";?>/>
        <br>

       <label>Port:</label>
        <input type="number" name="port"<?php if(isset($port)) echo " value='$port'";?>/>
        <br>

        <label>Album:</label>
        <select name="album_name">
          <option disabled selected value> -- select an album -- </option>
          <?php foreach ($albums as $album) { ?>
              <option value="<?php echo $album;?>">
                <?php echo $album;?>
              </option>
          <?php } ?>
        </select>
        <br>

        <label>&nbsp;</label>
        <input type="submit" value="Upload to Server" />
        <br>
    </form>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
