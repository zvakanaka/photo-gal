<footer>
  <?php $gitHub = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/")); ?>
  <a href="https://github.com/zvakanaka/photo-gal/blob/master/<?php echo $gitHub;?>">Code on GitHub</a>

  <span id="timestampy"><?php echo 'Last updated: <time datetime="'. date('c') . '">' . date('F j, Y', getlastmod()) . '</time>'; ?></span>

  <noscript id="deferred-styles2">
    <link rel="stylesheet" type="text/css" href="<?php echo $project_dir;?>/styles/main.css"/>
  </noscript>
  <script>
    //this is what google pageload recommends so I copied this:
    var loadDeferredStyles2 = function() {
      var addStylesNode2 = document.getElementById("deferred-styles2");
      var replacement = document.createElement("div");
      replacement.innerHTML = addStylesNode2.textContent;
      document.body.appendChild(replacement)
      addStylesNode2.parentElement.removeChild(addStylesNode2);
    };
    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
        webkitRequestAnimationFrame || msRequestAnimationFrame;
    if (raf) raf(function() { window.setTimeout(loadDeferredStyles2, 0); });
    else window.addEventListener('load', loadDeferredStyles2);
  </script>
</footer>

</body>
</html>
