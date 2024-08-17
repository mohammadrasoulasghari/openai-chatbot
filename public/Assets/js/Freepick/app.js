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
