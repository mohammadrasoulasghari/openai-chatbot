<div>
    @section('styles')
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

            /* Styles for the modal */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.8);
                align-items: center;
                justify-content: center;
            }

            .modal-content {
                background-color: #343541;
                margin: auto;
                padding: 30px;
                border: 1px solid #888;
                width: 80%;
                max-width: 700px;
                text-align: center;
                border-radius: 12px;
            }

            .modal img {
                max-width: 100%;
                height: auto;
            }

            .close {
                color: red;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #aaa;
                text-decoration: none;
                cursor: pointer;
            }

            .download-btn {
                margin-top: 30px;
                background-color: #0A84FF;
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
            }

            /* Loader Overlay */
            .loader-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                visibility: hidden;
            }

            /* Loader Animation */
            .loader {
                text-align: center;
                color: white;
            }

            .loader .spinner {
                border: 8px solid #f3f3f3;
                border-top: 8px solid #3498db;
                border-radius: 50%;
                width: 80px;
                height: 80px;
                animation: spin 1s linear infinite;
            }

            .loader p {
                margin-top: 15px;
                font-size: 18px;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    @endsection

    @section('content')
        <header class="header">
            <img src="https://7learn.com/assets/img/icons/logo-white.svg" alt="Logo">
        </header>
        <main class="main-container">
            <div class="form-container">
                <h2>تولید تصویر با Freepik API</h2>
                <form id="image-form" action="/generate-image" method="POST">
                    @csrf
                    <div class="form-group">
                        <div>
                            <label for="prompt">متن توصیفی:</label>
                            <input type="text" id="prompt" name="prompt" required placeholder="این بخش به زبان انگلیسی وارد شود">
                        </div>
                        <div>
                            <label for="negative_prompt">عباراتی که نباید در تصویر باشد:</label>
                            <input type="text" id="negative_prompt" name="negative_prompt" placeholder="این بخش به زبان انگلیسی وارد شود">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <label for="num_inference_steps">تعداد مراحل:</label>
                            <input type="number" id="num_inference_steps" name="num_inference_steps" value="8">
                        </div>
                        <div>
                            <label for="guidance_scale">سطح تطابق تصویر با توضیحات:</label>
                            <input type="number" id="guidance_scale" name="guidance_scale" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <label for="seed">seed:</label>
                            <input type="number" id="seed" name="seed" value="42">
                        </div>
                        <div>
                            <label for="image_size">اندازه تصویر:</label>
                            <select id="image_size" name="image_size">
                                <option value="square">مربع</option>
                                <option value="portrait">پرتره</option>
                                <option value="landscape">لنداسکیپ</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="flex: 1;">
                            <label for="style">استایل:</label>
                            <select id="style" name="style">
                                @foreach ($styles as $value => $label)
                                    <option value="{{ $value }}" {{ $value == $selectedStyle ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label for="color">رنگ:</label>
                            <select id="color" name="color">
                                @foreach ($colors as $value => $label)
                                    <option value="{{ $value }}" {{ $value == $selectedColor ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="flex: 1;">
                            <label for="lightning">نورپردازی:</label>
                            <select id="lightning" name="lightning">
                                @foreach ($lightnings as $value => $label)
                                    <option value="{{ $value }}" {{ $value == $selectedLightning ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label for="framing">کادر:</label>
                            <select id="framing" name="framing">
                                @foreach ($framings as $value => $label)
                                    <option value="{{ $value }}" {{ $value == $selectedFraming ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" id="submit-button" disabled>
                        <span class="button-icon">🎨</span> تولید تصویر
                    </button>
                </form>
            </div>
        </main>

        <!-- Loader Overlay -->
        <div id="loader-overlay" class="loader-overlay">
            <div class="loader">
                <div class="spinner"></div>
                <p>در حال تولید تصویر...</p>
            </div>
        </div>

        <!-- Modal for displaying generated image -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="generatedImage" src="" alt="Generated Image">
                <a id="downloadLink" href="#" class="download-btn" download="generated_image.png">دانلود تصویر</a>
            </div>
        </div>

        <div id="toast" class="toast">در حال تولید تصویر...</div>
    @endsection

    @section('script')
        <script>
            const form = document.getElementById("image-form");
            const submitButton = document.getElementById("submit-button");
            const toast = document.getElementById("toast");
            const modal = document.getElementById("myModal");
            const closeModal = document.querySelector(".close");
            const generatedImage = document.getElementById("generatedImage");
            const downloadLink = document.getElementById("downloadLink");
            const loaderOverlay = document.getElementById("loader-overlay");

            form.addEventListener("submit", (event) => {
                event.preventDefault();

                // پاک کردن تمامی اعلان‌ها و پیام‌های قبلی
                toast.classList.remove("show");
                modal.style.display = "none";

                // نمایش لودر
                loaderOverlay.style.visibility = 'visible';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        // پنهان کردن لودر
                        loaderOverlay.style.visibility = 'hidden';

                        if (data.images && data.images.length > 0) {
                            const base64Image = `data:image/png;base64,${data.images[0].base64}`;
                            generatedImage.src = base64Image;
                            downloadLink.href = base64Image;

                            // نمایش modal
                            modal.style.display = "flex";
                        } else {
                            alert(data.error || "خطایی در تولید تصویر رخ داده است.");
                        }
                    })
                    .catch(error => {
                        // پنهان کردن لودر
                        loaderOverlay.style.visibility = 'hidden';

                        alert("خطایی در ارسال درخواست رخ داد.");
                        console.error('Error:', error);
                    });
            });

            // بستن modal
            closeModal.onclick = function() {
                modal.style.display = "none";
            }

            // بستن modal با کلیک خارج از آن
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

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
    @endsection
</div>
