<div>
    @section('styles')
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
                height: 100%;
                background-color: #343541;
                border-radius: 8px;
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

            .message {
                padding: 10px;
                border-radius: 8px;
                max-width: 80%;
                font-family: 'Vazirmatn', Arial, sans-serif;
                display: inline-block;
                position: relative;
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

            .loader {
                display: inline-block;
                width: 18px;
                height: 18px;
                border: 2px solid rgba(255, 255, 255, 0.6);
                border-top: 2px solid white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin-left: 10px;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
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
                .message {
                    font-size: 14px;
                    padding: 15px;
                }

                .record-button {
                    font-size: 14px;
                    padding: 10px 20px;
                }
            }

            @media (max-width: 480px) {
                .message {
                    font-size: 12px;
                    padding: 10px;
                }

                .record-button {
                    font-size: 12px;
                    padding: 8px 16px;
                }
            }
        </style>
    @endsection
    @section('content')
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
    @endsection
    @section('script')
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
                                            // نمایش پیام کاربر با لودر
                                            const userMessage = document.createElement("p");
                                            userMessage.className = "message user-message";
                                            userMessage.innerHTML = data.transcription + ' <span class="loader"></span>';
                                            chatBox.appendChild(userMessage);

                                            // ارسال پیام کاربر به ChatGPT و دریافت پاسخ به صورت استریم
                                            const queryText = encodeURIComponent(data.transcription);
                                            const source = new EventSource(`/ask-gpt?question=${queryText}`);

                                            const botMessage = document.createElement("p");
                                            botMessage.className = "message bot-message";
                                            botMessage.innerHTML = '<span class="loader"></span>';
                                            chatBox.appendChild(botMessage);

                                            source.addEventListener("update", function (event) {
                                                if (event.data === "<END_STREAMING_SSE>") {
                                                    source.close();
                                                    botMessage.querySelector('.loader').remove();
                                                    return;
                                                }
                                                botMessage.innerText += event.data;
                                            });

                                            userMessage.querySelector('.loader').remove();
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
    @endsection
</div>
