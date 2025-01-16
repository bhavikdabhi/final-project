<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <style>
       body, html {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #2b2b2c;
    padding: 0 10px;
    margin: 0;
    height: 100%;
}
.container {
    background: #fff;
    max-width: 450px;
    width: 100%;
    border-radius: 16px;
    box-shadow: 0 0 128px 0 rgba(0,0,0,0.1),
                0 32px 64px -48px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
}
header {
    background-color: #3C91E6;
    color: #fff;
    padding: 10px;
    text-align: center;
    font-size: 20px;
}
.form {
    padding: 25px 30px;
}
#messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}
#messageInput {
    width: calc(100% - 90px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
    margin-right: 10px;
}
#sendButton {
    width: 80px;
    padding: 10px;
    background-color: #3C91E6;
    color: #fff;
    border: none;
    border-radius: 5px;
    outline: none;
    cursor: pointer;
}
#messageForm {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: #fff;
    height: 75px; /* Fixed height */
    flex-grow: 0;  /* Prevent auto-increasing */
    overflow: hidden; /* Prevent overflow */
}
@media screen and (max-width: 450px) {
    .form, .users {
        padding: 20px;
    }
    .form header {
        text-align: center;
    }
    .form form .name-details {
        flex-direction: column;
    }
    .form .name-details .field:first-child {
        margin-right: 0px;
    }
    .form .name-details .field:last-child {
        margin-left: 0px;
    }
    .users header img {
        height: 45px;
        width: 45px;
    }
    .users header .logout {
        padding: 6px 10px;
        font-size: 16px;
    }
    :is(.users, .users-list) .content .details {
        margin-left: 15px;
    }
    .users-list a {
        padding-right: 10px;
    }
    .chat-area header {
        padding: 15px 20px;
    }
    .chat-box {
        min-height: 400px;
        padding: 10px 15px 15px 20px;
    }
    .chat-box .chat p {
        font-size: 15px;
    }
    .chat-box .outogoing .details {
        max-width: 230px;
    }
    .chat-box .incoming .details {
        max-width: 265px;
    }
    .incoming .details img {
        height: 30px;
        width: 30px;
    }
    .chat-area form {
        padding: 20px;
    }
    .chat-area form input {
        height: 40px;
        width: calc(100% - 48px);
    }
    .chat-area form button {
        width: 45px;
    }
}

    </style>
   
</head>
 
<body>
    <div class="container">
    <section class="chat-area">
        <header>
            Chat App
        </header>
        <div class="chat-box"> </div>
        <div id="messages"></div>
        <form id="messageForm">
            <input type="text" id="messageInput" placeholder="Type your message...">
            <button type="submit" id="sendButton">Send</button>
        </form>
    </div>
    </section>
    </div>
    <script>
        // JavaScript code for handling messages
        document.getElementById('messageForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message !== '') {
                sendMessage(message);
                messageInput.value = '';
            }
        });

        // Function to send a message
        function sendMessage(message) {
            fetch('/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'message=' + encodeURIComponent(message)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }

        // Function to display messages
        function displayMessages(messages) {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML = '';
            messages.forEach(message => {
                const messageElement = document.createElement('div');
                messageElement.textContent = message;
                messagesDiv.appendChild(messageElement);
            });
        }

        // Function to fetch and display messages
        function fetchAndDisplayMessages() {
            fetch('/messages')
            .then(response => response.json())
            .then(data => {
                displayMessages(data);
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
        }

        // Fetch and display messages periodically
        setInterval(fetchAndDisplayMessages, 1000);
    </script>
</body>
</html>
