$(document).ready(function(){
    $("#minus_wrapper > img").on('click', function(){
        $(this).css({
            "display": "none"
        });
        $("#back").css({
            "display": "initial"
        });
        $(".table_delete").each(function(){
            $(this).css({
                "display": "table-cell",
                "width": "50px"
            });
        });
        $(".table_what").each(function(){
            $(this).css({
                "width": "300px"
            });
        });
        $(".delete_selected").css({
            "display": "inline-block"
        });
    });

    $("#back").on('click', function(){
        $(this).css({
            "display": "none"
        });
        $("#minus_wrapper > img").css({
            "display": "inline-block"
        });
        $(".table_delete").each(function(){
            $(this).css({
                "display": "none"
            });
        });
        $(".table_what").each(function(){
            $(this).css({
                "width": "350px"
            });
        });
        $(".delete_selected").css({
            "display": "none"
        });
    });



    $(".genre_work").on('click', function(){
        $(this).css({
            "borderBottom": "4px solid #8d12ff"
        });
        $(".genre_private, .genre_others").css({
            "borderBottom": "4px solid #ddb8ff"
        });
        $(".table_wrap").animate({
            "left": "0"
        });
    });
    
    $(".genre_private").on('click', function(){
        $(this).css({
            "borderBottom": "4px solid #8d12ff"
        });
        $(".genre_work, .genre_others").css({
            "borderBottom": "4px solid #ddb8ff"
        });
        $(".table_wrap").animate({
            "left": "-500px"
        });
    });

    $(".genre_others").on('click', function(){
        $(this).css({
            "borderBottom": "4px solid #8d12ff"
        });
        $(".genre_private, .genre_work").css({
            "borderBottom": "4px solid #ddb8ff"
        });
        $(".table_wrap").animate({
            "left": "-1000px"
        });
    });


    $(".dl_btn").on('click', function(){
        $(".deadline_form, .deadline_date, .deadline_submit, .dl_btn").css({
            "display": "none"
        });
    });

    $(".table_priority").on('click', function(){
        $(".pri_form").css({
            "display": "initial"
        });
        $(".pri_change", this).css({
            "display": "initial",
            "position": "absolute",
            "top":"0",
            "left": "0",
            "z-index": "1"
        });
        $(".pri_submit", this).css({
            "display": "initial",
            "position": "absolute",
            "top":"25",
            "left": "0",
            "z-index": "1"
        });
        $(".pri_btn", this).css({
            "display": "initial",
            "position": "absolute",
            "top": "25",
            "left": "50",
            "z-index": "1"
        });
    });

    $(".pri_btn").on('click', function(){
        $(".pri_form, .pri_change, .pri_submit, .pri_btn").css({
            "display": "none"
        });
    });

})