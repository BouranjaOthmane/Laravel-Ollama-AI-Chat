<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laravel AI Chat</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .chat-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            height: 500px;
            overflow-y: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
        }

        .message {
            margin-bottom: 15px;
        }

        .user {
            text-align: right;
        }

        .user span {
            background: #2563eb;
            color: white;
        }

        .assistant span {
            background: #e5e7eb;
        }

        .message span {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 80%;
        }

        .input-area {
            display: flex;
            margin-top: 20px;
            gap: 10px;
        }

        input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        button {
            background: #2563eb;
            color: white;
            border: 0;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
        }

        .typing {
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Laravel + Ollama AI Chat</h2>

        <div id="chatBox" class="chat-box"></div>

        <div class="input-area">
            <input type="text" id="message" placeholder="Ask something...">
            <button onclick="sendMessage()">Send</button>
        </div>

    </div>

    <script>
        let messages = [];
        async function sendMessage() {

            let input = document.getElementById('message');
            let message = input.value.trim();

            if (!message) return;

            let chatBox = document.getElementById('chatBox');

            chatBox.innerHTML += `
                <div class="message user">
                    <span>${message}</span>
                </div>
            `;

            input.value = '';

            chatBox.innerHTML += `
                <div class="message assistant" id="loading">
                    <span class="typing">Thinking...</span>
                </div>
            `;

            chatBox.scrollTop = chatBox.scrollHeight;

            messages.push({
                role: 'user',
                content: message
            });

            let response = await fetch('/ai-chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    messages: messages
                })
            });

           
            let data = await response.json();

             messages.push({
                role: 'assistant',
                content: data.reply
            });

            document.getElementById('loading').remove();

            chatBox.innerHTML += `
                <div class="message assistant">
                    <span>${data.reply}</span>
                </div>
            `;

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        document.getElementById('message')
            .addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
    </script>

</body>

</html>
