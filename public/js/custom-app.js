/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/custom-app.js ***!
  \************************************/
$(document).ready(function () {
  $('.delete-user').click(function (e) {
    var _this = this;

    e.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then(function (result) {
      if (result.isConfirmed) {
        var Url = $(_this).attr('href');
        var userId = $(_this).data('id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': csrfToken
          }
        });
        $.ajax({
          url: Url,
          type: 'DELETE',
          data: {
            "id": userId,
            "_token": csrfToken
          },
          success: function success(res) {
            if (res == 'true') {
              $('#user-' + userId).hide();
              Swal.fire('Deleted!', 'user deleted successfully.', 'success');
            }
          }
        });
      }
    });
  });
});
/******/ })()
;