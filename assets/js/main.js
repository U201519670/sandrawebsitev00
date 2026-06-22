(function () {
  'use strict';

  var toggle = document.querySelector('[data-nav-toggle]');
  var panel = document.querySelector('[data-nav-panel]');
  if (!toggle || !panel) return;

  var mq = window.matchMedia('(min-width: 768px)');

  function setOpen(open) {
    if (mq.matches) {
      panel.classList.remove('max-md:hidden');
      toggle.setAttribute('aria-expanded', 'false');
      return;
    }
    panel.classList.toggle('max-md:hidden', !open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  toggle.addEventListener('click', function () {
    var open = toggle.getAttribute('aria-expanded') === 'true';
    setOpen(!open);
  });

  mq.addEventListener('change', function () {
    setOpen(false);
  });
})();
