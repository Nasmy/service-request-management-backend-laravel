$(document).ready(function(){
    $('.delete-user').click(function(e){
     e.preventDefault();

     Swal.fire({
         title: 'Are you sure?',
         text: "You won't be able to revert this!",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, delete it!'
       }).then((result) => {

         if (result.isConfirmed) {

             let Url = $(this).attr('href');
             let userId = $(this).data('id');
             let csrfToken = $('meta[name="csrf-token"]').attr('content');
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
                     "_token": csrfToken,
                 },
                 success: function (res)
                 {
                     if(res == 'true'){
                         $('#user-'+userId).hide();
                         Swal.fire(
                         'Deleted!',
                         'user deleted successfully.',
                         'success'
                         )
                     }
                 }
             });

         }
       })

    })
 });
