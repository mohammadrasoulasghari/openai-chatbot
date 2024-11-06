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
            textarea {
                padding: 12px;
                border: 1px solid #50515E;
                border-radius: 8px;
                background-color: #40414F;
                color: white;
                font-size: 14px;
                font-family: 'Vazirmatn', sans-serif;
                transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
                box-sizing: border-box;
                min-height: 100px; /* حداقل ارتفاع برای textarea */
                resize: vertical; /* اجازه‌ی تغییر اندازه عمودی */
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
                width: 100%; /* این باعث می‌شود دکمه به صورت کامل عرض فرم را بگیرد */
                max-width: 300px; /* این باعث می‌شود دکمه یک اندازه محدود داشته باشد */
                margin-left: auto; /* اضافه شده برای مرکز کردن دکمه */
                margin-right: auto; /* اضافه شده برای مرکز کردن دکمه */
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
                animation: fadeIn 0.5s ease-out;
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
                animation: slideDown 0.5s ease-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes slideDown {
                from { transform: translateY(-20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
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
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }

            @keyframes slideUp {
                from { transform: translateY(0); opacity: 1; }
                to { transform: translateY(-20px); opacity: 0; }
            }

            .modal.fade-out {
                animation: fadeOut 0.5s ease-out forwards;
            }

            .modal-content.fade-out {
                animation: slideUp 0.5s ease-out forwards;
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
            .header-container {
                display: flex;
                align-items: center; /* ترازبندی عمودی در وسط */
                justify-content: center; /* ترازبندی افقی در وسط */
                gap: 10px; /* فاصله بین لوگو و متن */
            }

            .freepik-logo {
                width: 70px; /* عرض مناسب برای لوگو */
                height: auto; /* حفظ نسبت اصلی تصویر */
            }
            .toast.error {
                background-color: #ff4c4c; /* رنگ قرمز برای خطا */
                color: white;
            }

        </style>

    @endsection

    @section('content')
        <header class="header">
            <img src="https://7learn.com/assets/img/icons/logo-white.svg" alt="Logo">
        </header>
        <main class="main-container">
            <div class="form-container">
                <div class="header-container">
                    <h2>سیستم تولید تصویر با Freepik</h2>
                    <img src="https://7learnchatbot.mohammadrasoul.lol/images/freepik-logo-svgrepo-com.png" alt="Freepik Logo" class="freepik-logo">
                </div>
                <livewire:free-pick.free-pick-main-from />
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
                    <input type="text" id="editPrompt" placeholder="درخواست ویرایش تصویر را وارد کنید...">
                    <button id="submitEditButton">ویرایش تصویر</button>
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
            document.addEventListener("DOMContentLoaded", function() {
                checkFormValidity(); // بررسی اولیه برای فعال کردن دکمه

                form.querySelectorAll("input, select").forEach(input => {
                    input.addEventListener("input", () => {
                        checkFormValidity();
                    });
                });
            });
            form.addEventListener("submit", (event) => {
                event.preventDefault();
                console.log('asd')
                // پاک کردن تمامی اعلان‌ها و پیام‌های قبلی
                toast.classList.remove("show");
                modal.style.display = "none";

                let errorMessage = '';

                // بررسی خالی بودن فیلد prompt
                if (!form.prompt.value.trim()) {
                    errorMessage = 'متن توصیفی (prompt) باید پر شود.';
                }

                if (errorMessage) {
                    showToast(errorMessage, 'error');
                    return; // جلوگیری از ارسال فرم
                }

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
                            showToast(data.error || "خطایی در تولید تصویر رخ داده است.", 'error');
                        }
                    })
                    .catch(error => {
                        // پنهان کردن لودر
                        loaderOverlay.style.visibility = 'hidden';

                        showToast("خطایی در ارسال درخواست رخ داد.", 'error');
                        console.error('Error:', error);
                    });
            });

            // بستن modal
            closeModal.onclick = function() {
                modal.classList.add('fade-out');
                modal.querySelector('.modal-content').classList.add('fade-out');

                // پس از پایان انیمیشن، مودال را از دید خارج کن
                setTimeout(() => {
                    modal.style.display = "none";
                    modal.classList.remove('fade-out');
                    modal.querySelector('.modal-content').classList.remove('fade-out');
                }, 500); // زمان مشابه با مدت انیمیشن
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
            function showToast(message, type = '') {
                toast.classList.remove('show', 'error');
                if (type) {
                    toast.classList.add(type);
                }
                toast.textContent = message;
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show', type);
                }, 3000); // پیام پس از 3 ثانیه پنهان می‌شود
            }

            function checkFormValidity() {
                let allFilled = true;
                form.querySelectorAll("input[required]").forEach(requiredInput => {
                    if (!requiredInput.value.trim()) {
                        allFilled = false;
                    }
                });
                submitButton.disabled = !allFilled;
            }
            const submitEditButton = document.getElementById('submitEditButton');

            submitEditButton.addEventListener('click', () => {
                const editPrompt = document.getElementById('editPrompt').value;
                const imageUrl = generatedImage.src; // آدرس تصویر فعلی

                if (!editPrompt.trim()) {
                    showToast('لطفاً توضیحات مربوط به ویرایش تصویر را وارد کنید.', 'error');
                    return;
                }

                // نمایش لودر
                loaderOverlay.style.visibility = 'visible';

                // دریافت تصویر و تبدیل به blob
                fetch(imageUrl)
                    .then(res => res.blob())
                    .then(blob => {
                        return new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.onloadend = () => {
                                // ساخت یک تصویر جدید برای تبدیل به فرمت PNG
                                const img = new Image();
                                img.src = reader.result;
                                img.onload = () => {
                                    const canvas = document.createElement('canvas');
                                    canvas.width = img.width;
                                    canvas.height = img.height;
                                    const ctx = canvas.getContext('2d');
                                    ctx.drawImage(img, 0, 0);

                                    // تبدیل تصویر به PNG
                                    canvas.toBlob((blob) => {
                                        resolve(blob);
                                    }, 'image/png');
                                };
                            };
                            reader.onerror = reject;
                            reader.readAsDataURL(blob);
                        });
                    })
                    .then(pngBlob => {
                        const formData = new FormData();
                        formData.append('image', pngBlob, 'image.png');
                        formData.append('prompt', editPrompt);
                        formData.append('size', '1024x1024'); // سایز تصویر

                        return fetch('YOUR_BACKEND_ENDPOINT_FOR_IMAGE_EDIT', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: formData
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        // پنهان کردن لودر
                        loaderOverlay.style.visibility = 'hidden';

                        if (data.images && data.images.length > 0) {
                            const base64Image = `data:image/png;base64,${data.images[0].base64}`;
                            generatedImage.src = base64Image;
                            downloadLink.href = base64Image;
                        } else {
                            showToast(data.error || 'خطایی در ویرایش تصویر رخ داده است.', 'error');
                        }
                    })
                    .catch(error => {
                        // پنهان کردن لودر
                        loaderOverlay.style.visibility = 'hidden';

                        showToast('خطایی در ارسال درخواست رخ داد.', 'error');
                        console.error('Error:', error);
                    });
            });


        </script>
    @endsection
</div>
