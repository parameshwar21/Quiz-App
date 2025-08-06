


document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.needs-confirm').forEach(el => {
    el.addEventListener('click', function (e) {
      if (!confirm('Are you sure?')) {
        e.preventDefault();
      }
    });
  });
});
