$(document).ready(() => {
    $('.tabs').tabs();

    $('.dropdown-trigger').dropdown(
        {
            constrainWidth: false
        }
    );
    
    $('.sidenav').sidenav();
    $('.modal').modal();
    $('select').formSelect();
});