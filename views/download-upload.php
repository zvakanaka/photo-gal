<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>
<main>
  <div class="spacer"></div>
  <div class="center" id="download-album">
    <h2>Download from Camera</h2>
    <label for="">Album Name:
      <input type="text" class="nice" id="new-album-name" minLength="1" maxlength="32" required/>
      <p class="input-error-message"></p>
    </label>
      <div class="hidden spacer" id="progress-spacer"></div>
      <div class="nice hidden progress-bar" id="download-progress-bar">
        <div class="nice progress-bar-progress" id="download-progress-bar-progress">0%</div>
      </div>
    <div class="spacer"></div>
    <div>
      <button class="nice" id="download">📷 Download</button>
    </div>
  </div>

  <div class="spacer-big"></div>

  <div class="center" id="upload-to-server">
    <h2>Upload to Another Server</h2>
    <label for="">Domain:
      <input type="text" class="nice" id="domain-name"/>
    </label>
    <div class="spacer"></div>
      <label for="">User:
      <input title="User name on server" type="text" class="nice" id="user-name"/>
    </label>
    <div class="spacer"></div>
    <label for="">Port:
      <input title="Server's SSH Port" type="number" class="nice" id="port-number"/>
    </label>
    <div class="spacer"></div>
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

  <div class="spacer-big"></div>

  <div class="center" id="process-all">
    <h2>Process all Albums</h2>
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

<noscript id="deferred-styles3">
  <link rel="stylesheet" type="text/css" href="<?php echo $project_dir;?>/styles/photo-gal.css"/>
</noscript>
<script>
  //this is what google pageload recommends so I copied this:
  var loadDeferredStyles3 = function() {
    var addStylesNode3 = document.getElementById("deferred-styles3");
    var replacement = document.createElement("div");
    replacement.innerHTML = addStylesNode3.textContent;
    document.body.appendChild(replacement)
    addStylesNode3.parentElement.removeChild(addStylesNode3);
  };
  var raf = requestAnimationFrame || mozRequestAnimationFrame ||
      webkitRequestAnimationFrame || msRequestAnimationFrame;
  if (raf) raf(function() { window.setTimeout(loadDeferredStyles3, 0); });
  else window.addEventListener('load', loadDeferredStyles3);
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
