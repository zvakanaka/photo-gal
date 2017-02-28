<!-- lightbox -->
<div id="light" class="lightbox-fg line">
  <img id="lightbox-picture" alt="Lightbox Web Preview" src="img/loading-lightbox.png"/>
  <div id="lightbox-sidebar">
    <a id="close-lightbox" href="javascript:void(0)" class="lightbox-close"></a>
    <br><a class="arrow-glyph download-glyph" id="download-link"></a>
    <br><a class="rotate-glyph" href='javascript:void(0)' id="rotate-lightbox-img"></a>

    <br><a class="arrow-glyph left-glyph" href='javascript:void(0)' id="prev-picture"></a>
    <br><a class="arrow-glyph right-glyph" href='javascript:void(0)' id="next-picture"></a>
    <br>


      <?php if (!isset($_SESSION['logged_in'])) {
        $user_att = " hidden";
      } else { $user_att = "";}?>
      <br><a id="favorite" class="fave-glyph<?php echo $user_att;?>"></a>
      <br>
      <?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] == false) {
        $admin_att = " hidden";
      } else { $admin_att = "";}?>
      <br><a id="set-as-album-thumb" class="thumb-glyph<?php echo $admin_att;?>"></a>
      <br><a id="move-to-trash" class="hide-glyph<?php echo $admin_att;?>">Hide</a>
      <br><a id="delete-photo" class="delete-glyph<?php echo $admin_att;?>">Delete</a>
  </div>
</div>
<div id="fade" class="lightbox-bg" onclick="document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">
</div>

<!-- end lightbox -->
<script src="js/lightbox.js"></script>
<noscript id="deferred-styles">
  <link rel="stylesheet" type="text/css" href="<?php echo $project_dir;?>/styles/lightbox.css"/>
</noscript>
<script>
  //this is what google pageload recommends so I copied this:
  var loadDeferredStyles = function() {
    var addStylesNode = document.getElementById("deferred-styles");
    var replacement = document.createElement("div");
    replacement.innerHTML = addStylesNode.textContent;
    document.body.appendChild(replacement)
    addStylesNode.parentElement.removeChild(addStylesNode);
  };
  var raf = requestAnimationFrame || mozRequestAnimationFrame ||
      webkitRequestAnimationFrame || msRequestAnimationFrame;
  if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
  else window.addEventListener('load', loadDeferredStyles);
</script>
