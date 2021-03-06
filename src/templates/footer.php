<footer>
  <div class="footerInner">
    <!-- Logo du site -->
    <div class="logo">
      <a href="/index.php" draggable="false" ondragstart="return false"><img id="logoSite" src="/assets/img/logo_sebflix.png" alt="Sebflix" width="238.5" height="83.5" /></a>
    </div>
    <nav>
      <div class="social">
        <!-- Lien vers mon Portfolio -->
        <a href="/src/views/404.php" class="iconFooter" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/logo_portfolio.png" alt="Mon Portfolio" width="49" height="35" /></a>
        <!-- Lien vers mon LinkedIn -->
        <a href="https://www.linkedin.com/in/sébastien-flouriot-99aa75205" class="iconFooter" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/LinkedIn.png" alt="LinkedIn" width="35" height="35" /></a>
        <!-- Lien vers mon Gmail -->
        <a href="mailto:sebastien.flouriot@gmail.com" class="iconFooter" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/Gmail.png" alt="Gmail" width="35" height="35" /></a>
        <!-- Lien vers mon GitHub -->
        <a href="https://github.com/SebF33" class="iconFooter" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/GitHub.png" alt="GitHub" width="35" height="35" /></a>
        <a href="/index.php" class="copyright" draggable="false" ondragstart="return false">© Sebflix &mdash; Tous droits réservés.</a>
      </div>
      <div class="dev">
        <?php
        if (substr($_SERVER['PHP_SELF'], -9) !== 'about.php') { ?>
          <a href="/src/views/about.php" draggable="false" ondragstart="return false">À propos</a>
        <?php } ?>
        <a href="https://developer.mozilla.org/fr/docs/Web/Guide/HTML/HTML5" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/HTML.png" alt="HTML" width="35" height="35" /></a>
        <a href="https://developer.mozilla.org/fr/docs/Web/CSS" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/CSS.png" alt="CSS" width="31" height="35" /></a>
        <a href="https://developer.mozilla.org/fr/docs/Web/JavaScript" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/JavaScript.png" alt="JavaScript" width="35" height="35" /></a>
        <a href="https://developer.mozilla.org/fr/docs/Glossary/PHP" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/PHP.png" alt="PHP" width="51" height="35" /></a>
        <a href="https://developer.mozilla.org/fr/docs/Glossary/SQL" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/MySQL.png" alt="MySQL" width="51" height="35" /></a>
        <a href="https://www.adobe.com/fr/products/photoshop.html" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/Photoshop.png" alt="Photoshop" width="36" height="35" /></a>
        <a href="https://www.adobe.com/fr/products/illustrator.html" class="iconFooter iconDev" target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/Illustrator.png" alt="Illustrator" width="36" height="35" /></a>
      </div>
    </nav>
  </div>
  <script src="/assets/js/footer.js"></script>
</footer>