<?php
require_once '../db/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="chatForm">
        <input type="text" id="message_text" name="message_text" placeholder="Type your message here...">
        <button type="submit">Send</button>
    </form>
    <div id="message_area"></div>
    <script>
        function loadMessages() {
            $.ajax({
                url: "fetch_messages.php",
                method: "POST",
                data: {
                    to_user_id: to_user_id
                },
                success: function(response) {
                    $('#message_area').html(response);
                }
            });
        }
        setInterval(loadMessages, 2000); // Check for new messages every 2 seconds
        $(document).ready(function() {
            $('#chatForm').submit(function(e) {
                e.preventDefault();
                var from_user_id = $('#from_user_id').val();
                var to_user_id = $('#to_user_id').val();
                var message_text = $('#message_text').val();
                $.ajax({
                    type: "POST",
                    url: "insert_message.php",
                    data: {
                        from_user_id: from_user_id,
                        to_user_id: to_user_id,
                        message_text: message_text
                    },
                    success: function(response) {
                        $('#chatForm').reset();
                    }
                });
            });
        });
    </script>
</body>

</html>