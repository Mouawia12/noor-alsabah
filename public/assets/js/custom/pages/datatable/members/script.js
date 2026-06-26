$('#create_member').click(function() {

    console.log(1);
    $('#create-member-modal').removeClass('d-none');
    $('#create-member-modal').iziModal('open');
    getContacts(348);
});

