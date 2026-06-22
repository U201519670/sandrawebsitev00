<?php declare(strict_types=1); ?>
<script>
  (function () {
    const btn = document.getElementById('menu-btn');
    const mobileNav = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');

    if (btn && mobileNav && iconOpen && iconClose) {
      btn.addEventListener('click', () => {
        const open = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!open));
        mobileNav.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
      });

      window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
          mobileNav.classList.add('hidden');
          iconOpen.classList.remove('hidden');
          iconClose.classList.add('hidden');
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    if (window.lucide) {
      window.lucide.createIcons();
    }
  })();
</script>
