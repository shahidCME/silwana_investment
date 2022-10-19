$(document).on('click','.deleteRecord',function (e) {
    e.preventDefault();
    var action = $(this).attr('href');
    swal({
        title: "Are you sure?",
        text: "Do you want to delete this record",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
     window.location.href = action;
    } else {
        swal("Record is Safe");
    }
})
})
$('#data-table').DataTable();