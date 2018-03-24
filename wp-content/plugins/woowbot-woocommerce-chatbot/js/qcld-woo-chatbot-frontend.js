jQuery(function ($) {
    /******************************
     Jarvis Chat bot
     *********************************/
      //Global
    var userHitNum=0;
    var confirmaNotName=0;
    var infiniteChat=0;
    var chatInitialize=0;
    var wooChatBotVar=woo_chatbot_obj;

    $(document).ready(function(){
        //show it
        $('#woo-chatbot-ball-wrapper').css({
            'display':'block',
        });
        //WooChatBot icon  position.
        $('#woo-chatbot-icon-container').css({
            'right': wooChatBotVar.woo_chatbot_position_x + 'px',
            'bottom': wooChatBotVar.woo_chatbot_position_y + 'px'
        })
        //window resize.
        var widowH=$(window).height();
        if(widowH<=1200 && widowH>=700 ){
            var ballConH=parseInt(widowH*0.5);
            $('.woo-chatbot-ball-inner').css({ 'height':ballConH+'px'})

            $(window).resize(function(){
                var widowH=$(window).height();
                var ballConH=parseInt(widowH*0.5);
                $('.woo-chatbot-ball-inner').css({ 'height':ballConH+'px'})
            });
        }
        //Woo chat bot show and initial message.
        $(document).on('click', '#woo-chatbot-ball', function (event) {
            $("#woo-chatbot-ball-container").toggle();
            $('.woo-chatbot-ball-inner').slimScroll({height: '50hv',start : 'bottom'});
           if(chatInitialize==0){
               //Working only for bot not user.
               disable_message_editor();
            //Initiliaze message
            var botJoinMsg="<span class='woo-chatbot-agent-joint'><strong>"+wooChatBotVar.agent+" </strong> "+wooChatBotVar.agent_join+"</span>";
            $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
            setTimeout(function(){
                $("#woo-chatbot-messages-container li:last").css({'background-color': 'transparent','border':'none'}).html(botJoinMsg);
                $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                var botInitialeMsg="<span>"+wooChatBotVar.welcome+" <strong>"+wooChatBotVar.host+"!</strong> "+wooChatBotVar.asking_name+"</span>";
                setTimeout(function(){
                    $("#woo-chatbot-messages-container li:last").html(botInitialeMsg);
                    //enable user work
                    enable_message_editor();
                }, 1500);


            }, 1500);
               chatInitialize++;
           }
        });
        //Hide Woo chat bot box if click on outside of icon.
        $(document).on('click',function (e) {
            var container = $("#woo-chatbot-ball-container");
            var rejectContainer = $("#woo-chatbot-ball");
            if(!rejectContainer.is(e.target) && rejectContainer.has(e.target).length === 0){
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.fadeOut(500);
                }
            }
        });
        //For send button click
        $(document).on('click',"#woo-chatbot-send-message",function(){
            userHitNum++;
            user_action();
        })
        //For keyboard enter.
        $("#woo-chatbot-editor").on('keypress',function(event) {
            if (event.which == 13) {
                event.preventDefault();
                userHitNum++;
                user_action();
            }
        });

    });


    /******* Bot and user interaction start here***************/
    function user_action(){
        var d = document;
        var userText =$("#woo-chatbot-editor").val();
        if(userText != ""){
            $("#woo-chatbot-messages-container").append("<li class='woo-chat-user-msg'><span>"+userText+"<span></li>");
            //scroll at the last message.
            $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
            bot_action(userText);
            $("#woo-chatbot-editor").val("");
        }
    }

    function bot_action(userText) {
        //Disable the input and button when bot will start working..
         disable_message_editor();
        $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
        infiniteChat=userText
        //Greeting and Name asking part.
        if(userHitNum ==1 && infiniteChat!=1){
            //Checking some common answer excpet name.
            var notName=["sure", "yes","yea", "yeah","no","nope","certainly","never"];
            if(notName.indexOf(userText)>-1 && confirmaNotName==0){
                var wooChatBotMsg = "<strong>"+userText+"</strong>  is your name?";
                userHitNum--;
                confirmaNotName++;
                //Asking Name again!
                setTimeout(function(){
                    $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                    //Afer 1.5 second show asking again.
                    setTimeout(function(){
                    $("#woo-chatbot-messages-container li:last").html("<span>Would you please confirm your name?<span>");
                        //scroll at the last message.
                        $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
                        //enable user work
                        enable_message_editor();

                    }, 1500);
                }, 2000);

            }else{
                var wooChatBotMsg =wooChatBotVar.i_am +" <strong>"+wooChatBotVar.agent+"</strong>! "+wooChatBotVar.name_greeting+", <strong>"+userText+"</strong>!";
                //Asking for typing a product!
                setTimeout(function(){
                    $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                    //Afer 1.5 second show product asking.
                    setTimeout(function(){
                        $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotVar.product_asking+"<span>");
                        //scroll at the last message.
                        $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
                        //enable user work
                        enable_message_editor();

                        }, 1500);
                }, 2000);
            }
            setTimeout(function(){
                $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotMsg+"<span>");
                //scroll at the last message.
                $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');

            }, 1500);
        }
        //For infinite asking answering
        if(userHitNum ==1 && infiniteChat==1){
            setTimeout(function(){
               $("#woo-chatbot-messages-container li:last").html('<span>'+wooChatBotVar.product_infinite+'<span>');
                //scroll at the last message.
                $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
                //enable user work
                enable_message_editor();

                }, 2500);
        }

        //Product handling steps.
        if(userHitNum ==2){
            //Searching product using given user strings.
            var data = {
                'action':'qcld_woo_chatbot_keyword',
                'keyword':userText,
            };
            $.post(wooChatBotVar.ajax_url, data, function (response) {
                // console.log(response);
                if(response.product_num==0){
                    var wooChatBotMsg = wooChatBotVar.product_fail+" <strong>"+userText+"</strong>!";
                    //suggesting product by category.
                    setTimeout(function(){
                        $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                        //Afer 1.5 second show suggesting.
                        setTimeout(function(){
                            $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotVar.product_suggest+"<span>");
                            //scroll at the last message.
                            $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');

                        }, 1500);
                    }, 2000);

                    //Getting the category by ajax to show at the bottom of chat box.
                    var cat_data = {
                        'action':'qcld_woo_chatbot_category',
                    };
                    $.post(wooChatBotVar.ajax_url, cat_data, function (cat_response) {
                        // $("#bot-bottom").html(cat_response);
                        //Append the category list as chat bot response
                        setTimeout(function(){
                            $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                            //Afer 1.5 second show categories.
                            setTimeout(function(){
                            $("#woo-chatbot-messages-container li:last").css({'background-color': 'transparent','border':'none'}).html("<div>"+cat_response +"</div>");
                                //scroll at the last message.
                                $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');

                            }, 1500);
                        }, 3500);
                    });
                }else{
                    var wooChatBotMsg = wooChatBotVar.product_success+" <strong>"+userText+"</strong>!";
                   //Showing product from ajax response
                    setTimeout(function(){
                            $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                            setTimeout(function(){
                            //Afer 1.5 second show categories.
                            $("#woo-chatbot-messages-container li:last").css({'background-color': 'transparent','border':'none','width':'100%'}).html(response.html);
                              //scroll at the last message.
                               $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');

                            }, 1500);

                        }, 2500);
                    //Setting infinite value as
                    setTimeout(function(){
                        $("#woo-chatbot-send-message").prop("disabled", true);
                        userHitNum=1;
                        bot_action(1);
                    }, 6000);
                }
                setTimeout(function(){
                $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotMsg+"<span>");
                    //scroll at the last message.
                    $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
                    //enable user work
                    //enable_message_editor();

                    }, 1500);
            });
        }
        //category handling steps.
        if(userHitNum ==3){
            var userTexts=userText.split("#");
            var categoryTitle=userTexts[0];
            var categoryId=userTexts[1];
            //Getting product by clicked category.
            var data = {
                'action':'qcld_woo_chatbot_category_products',
                'category':categoryId,
            };
            $.post(wooChatBotVar.ajax_url, data, function (response) {
                if(response.product_num==0){
                    var wooChatBotMsg = wooChatBotVar.product_fail+" <strong>"+categoryTitle+"</strong>!";
                    $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotMsg+"</span>");
                    $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                    //scroll at the last message.
                    $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
                    //suggesting product by category.
                    setTimeout(function(){
                         //Afer 1.5 second show suggesting.
                        setTimeout(function(){
                            $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotVar.product_infinite+"<span>");
                            //scroll at the last message.
                            $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');
                            //enable user work
                            enable_message_editor();

                            }, 1500);
                    }, 2000);

                } else{
                    //Now show chat message to choose the product.
                    var wooChatBotMsg = wooChatBotVar.product_success+" <strong>"+categoryTitle+"</strong>!";
                    //Showing Chat boat message with product.
                    $("#woo-chatbot-messages-container li:last").html("<span>"+wooChatBotMsg+"<span>");
                    //scroll at the last message.
                    $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');

                    $("#woo-chatbot-messages-container").append('<li class="woo-chatbot-msg"><img class="woo-chatbot-comment-loader" src="'+wooChatBotVar.image_path+'comment.gif" alt="Typing..." /></li>');
                    setTimeout(function(){
                        $("#woo-chatbot-messages-container li:last").css({'background-color': 'transparent','border':'none','width':'100%'}).html(response.html);
                        //scroll at the last message.
                        $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');


                    }, 1500);
                    //Setting infinite value as
                    setTimeout(function(){
                        userHitNum=1;
                        bot_action(1);
                    }, 5500);

                }


            });

        }

    }
    //When user click on the category then product will be show.
    $(document).on('click','.qcld-chatbot-product-category',function(){
        userHitNum++;
        var nameCatID=$(this).text()+'#'+$(this).attr('data-category-id');
        //Now hide the category and show the category for user.
        $("#woo-chatbot-messages-container .woo-chatbot-msg:last").fadeOut(1500);
        $("#woo-chatbot-messages-container").append("<li class='woo-chat-user-msg'><span>"+$(this).text()+"<span></li>");
        //scroll at the last message.
        $('.woo-chatbot-ball-inner').animate({ scrollTop: $('#woo-chatbot-messages-container').prop("scrollHeight")}, 'slow');

        bot_action(nameCatID);
    });
    function disable_message_editor(){
        $("#woo-chatbot-editor").attr('placeholder',wooChatBotVar.agent+' is typing...');
        $("#woo-chatbot-editor").attr('disabled',true);
        $("#woo-chatbot-send-message").attr('disabled',true);
    }
    function enable_message_editor(){
        $("#woo-chatbot-editor").attr('disabled',false).focus();
        $("#woo-chatbot-editor").attr('placeholder','Send a message.');
        $("#woo-chatbot-send-message").attr('disabled',false);
    }

});