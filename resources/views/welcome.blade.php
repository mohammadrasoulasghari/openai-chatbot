<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Style Chatbot</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #202123;
            font-family: 'Vazirmatn', Arial, sans-serif;
            color: white;
            direction: ltr;
        }

        .chat-container {
            width: 100%;
            max-width: 100%;
            height: 100%;
            background-color: #343541;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .chat-header {
            background-color: #202123;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid #444654;
        }

        .chat-header img {
            max-width: 150px;
            height: auto;
        }

        .chat-box {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-self: revert;
        }

        .chat-input {
            display: flex;
            flex-direction: row-reverse;
            padding: 10px;
            border-top: 1px solid #444654;
            background-color: #40414F;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 20px;
            background-color: #343541;
            color: white;
            outline: none;
            font-family: 'Vazirmatn', Arial, sans-serif;
        }

        .chat-input button {
            background-color: #0A84FF;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Vazirmatn', Arial, sans-serif;
        }

        .chat-input button:hover {
            background-color: #0077E6;
        }

        .message {
            padding: 10px;
            border-radius: 8px;
            max-width: 80%;
            font-family: 'Vazirmatn', Arial, sans-serif;
            display: inline-block;
        }

        .user-message {
            align-self: flex-end;
            background-color: #0A84FF;
            text-align: right;
        }

        .bot-message {
            align-self: flex-start;
            background-color: #444654;
            text-align: left;
        }

        /* Modal Styles */
        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        .modal-content {
            background-color: #343541;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            animation: fadeIn 0.5s ease;
        }

        .modal-content h2 {
            margin: 0 0 20px;
            font-size: 24px;
        }

        .modal-button {
            padding: 10px 20px;
            margin: 10px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Vazirmatn', Arial, sans-serif;
        }

        .openai-button {
            background-color: #0A84FF;
            color: white;
        }

        .openai-button:hover {
            background-color: #0077E6;
        }

        .avalai-button {
            background-color: #34A853;
            color: white;
        }

        .avalai-button:hover {
            background-color: #2C8C46;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .chat-container {
                max-width: 100%;
                max-height: 100vh;
                border-radius: 0;
            }

            .chat-header img {
                max-width: 120px;
            }

            .chat-box {
                padding: 10px;
                gap: 8px;
            }

            .chat-input {
                padding: 8px;
            }

            .chat-input input,
            .chat-input button {
                padding: 8px;
                font-size: 14px;
            }

            .message {
                font-size: 14px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .chat-container {
                max-width: 100%;
                max-height: 100vh;
                border-radius: 0;
            }

            .chat-header img {
                max-width: 100px;
            }

            .chat-box {
                padding: 8px;
                gap: 6px;
            }

            .chat-input {
                padding: 6px;
            }

            .chat-input input,
            .chat-input button {
                padding: 6px;
                font-size: 12px;
            }

            .message {
                font-size: 12px;
                padding: 6px;
            }
        }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <img src="https://7learn.com/assets/img/icons/logo-white.svg" alt="Logo">
    </div>
    <div id="chat-box" class="chat-box">
        <!-- Messages will appear here -->
    </div>
    <form id="form-question" class="chat-input" autocomplete="off">
        <input type="text" name="input" placeholder="پیامت را اینجا بنویس..." required />
        <button type="submit">ارسال</button>
    </form>
</div>

<!-- Modal for service selection -->
<div id="service-modal" class="modal">
    <div class="modal-content">
        <h2>انتخاب سرویس چت</h2>
        <button class="modal-button openai-button" data-service="openai">OpenAI</button>
        <button class="modal-button avalai-button" data-service="avalai">AvalAI</button>
    </div>
</div>

<script>
    const form = document.querySelector("#form-question");
    const chatBox = document.getElementById("chat-box");
    const serviceModal = document.getElementById("service-modal");
    let selectedService = null;

    // Function to handle service selection
    document.querySelectorAll(".modal-button").forEach(button => {
        button.addEventListener("click", (event) => {
            selectedService = event.target.dataset.service;
            serviceModal.style.display = "none";
        });
    });

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        if (!selectedService) {
            alert("لطفاً یک سرویس را انتخاب کنید.");
            return;
        }

        const input = event.target.input.value.trim();
        if (input === "") return;

        // Add user message
        const userMessage = document.createElement("p");
        userMessage.className = "message user-message";
        userMessage.innerText = input;
        chatBox.appendChild(userMessage);

        // Clear input
        event.target.input.value = "";

        const queryQuestion = encodeURIComponent(input);
        const source = new EventSource(`/ask?question=${queryQuestion}&service=${selectedService}`);

        // Create a bot message element
        const botMessage = document.createElement("p");
        botMessage.className = "message bot-message";
        chatBox.appendChild(botMessage);

        source.addEventListener("update", function (event) {
            if (event.data === "<END_STREAMING_SSE>") {
                source.close();
                return;
            }
            botMessage.innerText += event.data;
        });

        // Scroll to the latest message
        chatBox.scrollTop = chatBox.scrollHeight;
    });

    // Show modal on page load
    window.addEventListener("load", () => {
        serviceModal.style.display = "flex";
    });
</script>
</body>
</html>
