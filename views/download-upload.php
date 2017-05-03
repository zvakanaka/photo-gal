<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
<main>
  <h2>Download from DSLR</h2>
  <div class="center wrap" id="download-album">
    <label>Album Name:
      <input type="text" class="nice" id="new-album-name" minLength="1" maxlength="32" required/>
      <p class="input-error-message"></p>
    </label>
      <div class="hidden" id="progress-spacer"></div>
      <div class="nice hidden progress-bar" id="download-progress-bar">
        <div class="nice progress-bar-progress" id="download-progress-bar-progress">0%</div>
      </div>
    <div>
      <button class="nice" id="download">📷 Download</button>
    </div>
  </div>

  <h2>Upload to Another Server</h2>
  <div class="center wrap" id="upload-to-server">
      <label for="">Domain:
        <input type="text" class="nice" id="domain-name"/>
      </label>
        <label for="">User:
        <input title="User name on server" type="text" class="nice" id="user-name"/>
      </label>
      <label for="">Port:
        <input title="Server's SSH Port" type="number" class="nice" id="port-number"/>
      </label>
      <label for="">Album:
        <select title="Select an existing album to upload" class="nice" id="album-select" name="album_name">
          <option disabled selected value> - </option>
          <?php foreach ($albums as $album) { ?>
              <option value="<?php echo $album;?>">
                <?php echo $album;?>
              </option>
          <?php } ?>
        </select>
      </label>
      <div class="spacer"></div>
      <div>
        <button title="Save form to save typing next time" class="nice" id="save-server-details">📄 Save Form</button>
        <button title="Upload selected album via SCP" class="nice" id="upload">⬆️ Upload</button>
      </div>
      <div class="nice hidden" id="upload-message">
        <p></p>
      </div>
    </div>

  <h2>Process all Albums</h2>
  <div class="center" id="process-all">
    <div>
      <button title="Generate thumbs, webs, and album thumbnails for albums" class="nice" id="process">Process</button>
    </div>
  </div>
</main>
<!-- <script src="js/dslr.js"></script> -->
<script src="js/helpers.js"></script>
<?php if (isset($message) && isset($num_images_on_camera)) { ?>
  <script type="text/javascript">
    updateNumImagesProgress('<?php echo $new_album;?>', '<?php echo $num_images_on_camera;?>');
  </script>
<?php } ?>
<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
