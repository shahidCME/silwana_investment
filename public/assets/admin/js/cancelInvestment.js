$(document).on('click','.comment',function(){
    var comment = $(this).data('comment');
    $('#dynamic_comment').html(comment);

});