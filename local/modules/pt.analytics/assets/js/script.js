$(function() {
    function clickTrace(){
        console.log('Ok');
    }

    $(".analytics-link").on( "click", function (event) {
        event.preventDefault();
        clickTrace();

    });
});
