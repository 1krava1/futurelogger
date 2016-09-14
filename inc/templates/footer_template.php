<?php ob_start(); ?>
    </main>
    <footer id="footer">
      Nick Kravchenko
    </footer>
  </body>
</html>
<?php $out = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
<?php return $out; ?>