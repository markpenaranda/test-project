$(document).ready(function(){

	var socket = io(window.liveServerUrl);
    var userId = $('#userId').val();
    var roomId = $('#roomId').val();
    socket.on('chatroom-' + roomId, function(msg){
    	
        console.log(msg);
        if(msg.user_id != userId) { 
        	message = new Message({
                text: msg.message,
                message_side: 'left'
            });
        	 $messages = $('.messages');
        	 message.draw();
             $messages.animate({ scrollTop: $messages.prop('scrollHeight') }, 300);

         }
    });

	var Message;
    Message = function (arg) {
        this.text = arg.text, this.message_side = arg.message_side;
        this.draw = function (_this) {
            return function () {
                var $message;
                $message = $($('.message_template').clone().html());
                $message.addClass(_this.message_side).find('.text').html(_this.text);
                $('.messages').append($message);
                return setTimeout(function () {
                    return $message.addClass('appeared');
                }, 0);
            };
        }(this);
        return this;
    };



	$('.send').on('click', function(){
		var message = $('#message').val();
		sendMessage(message);
	})

	$('#message').keypress(function(event){
	    var keycode = (event.keyCode ? event.keyCode : event.which);
	    if(keycode == '13'){
	    	var message = $(this).val();
	        sendMessage(message);
	    }
	});

	sendMessage = function (text) {
            var $messages, message;
            if (text.trim() === '') {
                return;
            }
            $('.message_input').val('');
            var userId = $('#userId').val();
            var roomId = $('#roomId').val();
            $messages = $('.messages');
            message_side = "right";
            message = new Message({
                text: text,
                message_side: message_side
            });

            // socket.emit("chat message", JSON.stringify({message: text, user_id: userId}));

            $.get(window.liveServerUrl + '/chat/' + roomId , {message: text, user_id: userId}, function(data){
            	
	            message.draw();
            	return $messages.animate({ scrollTop: $messages.prop('scrollHeight') }, 300);
            });

        };


});



  // io.on('connection', function(socket){
  // socket.on('chat message', function(msg){
  //   console.log('message: ' + msg);
  // });
// });