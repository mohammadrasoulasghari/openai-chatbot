<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Voice Interaction</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            height:  100%;
            background-color: #343541;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
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
            justify-content: flex-start;
            align-items: center;
            padding-bottom: 70px; /* فضای کافی برای دکمه ضبط */
        }

        .text-display {
            background-color: #444654;
            padding: 20px;
            border-radius: 8px;
            max-width: 80%;
            color: white;
            text-align: center;
            font-family: 'Vazirmatn', Arial, sans-serif;
        }

        .record-button-container {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 10px 0;
            background-color: #202123;
            border-top: 1px solid #444654;
        }

        .record-button {
            background-color: #0A84FF;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Vazirmatn', Arial, sans-serif;
            font-size: 16px;
            display: flex;
            align-items: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        .record-button:hover {
            background-color: #0077E6;
        }

        .record-button:active {
            background-color: #005bb5;
        }

        .record-button span {
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .text-display {
                font-size: 14px;
                padding: 15px;
            }

            .record-button {
                font-size: 14px;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .text-display {
                font-size: 12px;
                padding: 10px;
            }

            .record-button {
                font-size: 12px;
                padding: 8px 16px;
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
    <div class="record-button-container">
        <button id="record-button" class="record-button">
            <span>🔴</span> ضبط صدا
        </button>
    </div>
</div>

<script>
    const recordButton = document.getElementById("record-button");
    const chatBox = document.getElementById("chat-box");
    let isRecording = false;
    let audioChunks = [];
    let mediaRecorder;

    if (navigator.mediaDevices && window.MediaRecorder) {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = function (event) {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = function () {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    audioChunks = [];

                    const formData = new FormData();
                    formData.append('audio', audioBlob);

                    fetch('/convert-audio-to-text', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: formData,
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.transcription) {
                                const queryText = encodeURIComponent(data.transcription);
                                const source = new EventSource(`/ask-gpt?question=${queryText}`);

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

                                chatBox.scrollTop = chatBox.scrollHeight;
                            } else {
                                alert("خطایی در تبدیل صوت به متن رخ داده است.");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                };
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
                recordButton.disabled = true;
            });

        recordButton.addEventListener("click", () => {
            if (isRecording) {
                mediaRecorder.stop();
                isRecording = false;
                recordButton.innerHTML = '<span>🔴</span> ضبط صدا';
            } else {
                mediaRecorder.start();
                isRecording = true;
                recordButton.innerHTML = '⏹️ توقف';
            }
        });
    } else {
        alert("مرورگر شما از ضبط صدا پشتیبانی نمی‌کند.");
        recordButton.disabled = true;
    }
</script>
</body>
</html>
