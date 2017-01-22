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

    window.reinitiateSocket = function (roomId) {
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
    }

    var currentDateTime = function () {
        var currentdate = new Date(); 
        var datetime = "Sent : " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();

        return datetime;
    }

	var Message;
    Message = function (arg) {
        this.text = arg.text, this.message_side = arg.message_side;
        this.draw = function (_this) {
            return function () {
                var $message;
                if(_this.message_side == "left") {
                    $message = $($('#reciever_template').clone().html());
                }
                else {
                    $message = $($('.message_template').clone().html());
                }
                $message.addClass(_this.message_side).find('.text').html(_this.text);
                $message.find('.message_date_time').html(currentDateTime);
                $('.messages').append($message);
                return setTimeout(function () {
                    return $message.addClass('appeared');
                }, 0);
            };
        }(this);
        return this;
    };




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
