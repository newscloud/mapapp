$(document).ready(function() {
  $('body')
  .on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); })
  .on('touchstart.dropdown', '.dropdown-submenu', function (e) { e.preventDefault(); });

});
