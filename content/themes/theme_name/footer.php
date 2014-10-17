  <footer class="global-footer">
    <div class="container">
      <div class="quote">“<?php echo get_field('global_footer_tagline', 'options'); ?>”</div>
      
      <div class="content">
        <div class="contact">
          <p>Contact Us</p>
          <p><a href="mailto:<?php echo get_field('email_address', 'options'); ?>"><?php echo get_field('email_address', 'options'); ?></a></p>
        </div>
        
        <div class="newsletter">
          <a class="button" href="#">Join our Mailing List</a>
        </div>
        
        <div class="connect">
          <p>Connect With Us</p>
          
          <ul class="social-media-links">
            <li><a href="#">facebook</a></li>
            <li><a href="#">twitter</a></li>
            <li><a href="#">google</a></li>
          </ul>
        </div>
      </div>
      
      <div class="copyright">© <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All Rights Reserved.</div>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>