<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تولید تصویر با Freepik</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #202123;
            color: white;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #202123;
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #444654;
        }

        .header img {
            max-width: 150px;
            height: auto;
        }

        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background-color: #343541;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        h2 {
            text-align: center;
            color: #0A84FF;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            padding: 5px;
        }

        .form-group > div {
            flex: 1;
            min-width: calc(50% - 15px);
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        label {
            font-size: 14px;
            font-weight: 500;
        }

        input[type="text"], input[type="number"], select {
            padding: 12px;
            border: 1px solid #50515E;
            border-radius: 8px;
            background-color: #40414F;
            color: white;
            font-size: 14px;
            font-family: 'Vazirmatn', sans-serif;
            transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }

        input[type="text"]::placeholder, input[type="number"]::placeholder, select {
            font-family: 'Vazirmatn', sans-serif;
        }

        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            background-color: #50515E;
            border-color: #0A84FF;
            box-shadow: 0 0 8px rgba(10, 132, 255, 0.5);
            outline: none;
        }

        button {
            padding: 15px;
            border: 1px solid #0A84FF;
            border-radius: 8px;
            background-color: #0A84FF;
            color: white;
            font-size: 16px;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0077E6;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .button-icon {
            margin-left: 8px;
            font-size: 18px;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #444654;
            padding: 10px 20px;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s;
        }

        .toast.show {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                width: 90%;
            }
            .form-group > div {
                min-width: 100%;
            }
            button {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 15px;
                width: 100%;
            }
            input[type="text"], input[type="number"], select {
                padding: 10px;
                font-size: 12px;
            }
            button {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <img src="https://7learn.com/assets/img/icons/logo-white.svg" alt="Logo">
</header>

<main class="main-container">
    <div class="form-container">
        <h2>تولید تصویر با Freepik API</h2>
        <form id="image-form" action="{{ route('generate.image') }}" method="POST">
            @csrf
            <div class="form-group">
                <div>
                    <label for="prompt">متن توصیفی:</label>
                    <input type="text" id="prompt" name="prompt" required placeholder="مثال: یک ماشین قرمز در جاده">
                </div>
                <div>
                    <label for="negative_prompt">عباراتی که نباید در تصویر باشد:</label>
                    <input type="text" id="negative_prompt" name="negative_prompt" placeholder="مثال: بدون انسان">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="num_inference_steps">تعداد مراحل:</label>
                    <input type="number" id="num_inference_steps" name="num_inference_steps" value="8">
                </div>
                <div>
                    <label for="guidance_scale">مقیاس راهنمایی:</label>
                    <input type="number" id="guidance_scale" name="guidance_scale" value="1">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="num_images">تعداد تصاویر:</label>
                    <input type="number" id="num_images" name="num_images" value="1">
                </div>
                <div>
                    <label for="seed">بذر:</label>
                    <input type="number" id="seed" name="seed" value="42">
                </div>
            </div>
            <div class="form-group">
                <div style="flex: 1;">
                    <label for="image_size">اندازه تصویر:</label>
                    <select id="image_size" name="image_size">
                        <option value="square">مربع</option>
                        <option value="portrait">پرتره</option>
                        <option value="landscape">لنداسکیپ</option>
                    </select>
                </div>
            </div>
            <button type="submit" id="submit-button" disabled>
                <span class="button-icon">🎨</span> تولید تصویر
            </button>
        </form>
    </div>
</main>

<div id="toast" class="toast">در حال تولید تصویر...</div>

<script>
    const form = document.getElementById("image-form");
    const submitButton = document.getElementById("submit-button");
    const toast = document.getElementById("toast");

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        // نمایش Toast
        toast.classList.add("show");

        // شبیه‌سازی درخواست API و تأخیر برای نمایش Toast
        setTimeout(() => {
            toast.classList.remove("show");
            alert("تصویر با موفقیت تولید شد!");  // این پیام را می‌توانید با فرآیند واقعی جایگزین کنید
        }, 3000);
    });

    // فعال کردن دکمه ارسال فرم زمانی که همه فیلدها پر شده باشد
    form.querySelectorAll("input, select").forEach(input => {
        input.addEventListener("input", () => {
            let allFilled = true;
            form.querySelectorAll("input[required]").forEach(requiredInput => {
                if (!requiredInput.value.trim()) {
                    allFilled = false;
                }
            });
            submitButton.disabled = !allFilled;
        });
    });
</script>
</body>
</html>
