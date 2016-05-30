$(document).ready(function(){
                $(".single-bottom").hide();
                $("[class^=tab] ul").click(function(){
                $(this).parent().find('.single-bottom').slideToggle(300);
                $(this).parent().siblings("[class^=tab]").find('.single-bottom').hide();
                })
              });

