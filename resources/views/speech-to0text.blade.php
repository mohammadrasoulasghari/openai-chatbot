<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speech to Text Chat</title>
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
            justify-content: center;
            align-items: center;
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

        .record-button {
            background-color: #0A84FF;
            color: white;
            border: none;
            padding: 15px 30px;
            margin-top: 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Vazirmatn', Arial, sans-serif;
            font-size: 16px;
            display: flex;
            align-items: center;
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
        <div id="text-display" class="text-display">
            متنت اینجا نمایش داده می‌شود
        </div>
        <button id="record-button" class="record-button">
            <span>🔴</span> ضبط صدا
        </button>
    </div>
</div>

<script>
    const recordButton = document.getElementById("record-button");
    const textDisplay = document.getElementById("text-display");

    let isRecording = false;
    let audioChunks = [];
    let mediaRecorder;

    if (navigator.mediaDevices && window.MediaRecorder) {
        navigator.mediaDevices.getUserMedia({audio: true})
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = function (event) {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = function () {
                    const audioBlob = new Blob(audioChunks, {type: 'audio/wav'});
                    audioChunks = [];

                    const formData = new FormData();
                    formData.append('audio', audioBlob);
                    formData.append('service', 'openai'); // یا 'avalai' بر اساس انتخاب کاربر

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('/speech', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.transcription) {
                                textDisplay.innerText = `متن: ${data.transcription}`;
                            } else {
                                textDisplay.innerText = "خطایی رخ داده است. لطفاً دوباره امتحان کنید.";
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            textDisplay.innerText = "خطا در ارسال فایل صوتی.";
                        });

                };
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
                textDisplay.innerText = "دسترسی به میکروفون امکان‌پذیر نیست.";
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
                textDisplay.innerText = "در حال ضبط صدا...";
                recordButton.innerHTML = '⏹️ توقف';
            }
        });
    } else {
        textDisplay.innerText = "مرورگر شما از ضبط صدا پشتیبانی نمی‌کند.";
        recordButton.disabled = true;
    }

</script>


</body>
</html>
