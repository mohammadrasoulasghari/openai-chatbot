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
                <option value="social_post">پست اجتماعی</option>
                <option value="social_story">استوری اجتماعی</option>
                <option value="standard">استاندارد</option>
                <option value="rectangular">مستطیل</option>
                <option value="widescreen" selected>وایداسکرین</option>
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
