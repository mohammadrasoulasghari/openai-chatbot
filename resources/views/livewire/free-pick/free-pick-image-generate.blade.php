<div>
    @section('styles')
        <link rel="stylesheet" href="{{asset('Assets/css/Freepick/app.css')}}">
    @endsection

    @section('content')
        <header class="header">
            <img src="https://7learn.com/assets/img/icons/logo-white.svg" alt="Logo">
        </header>
        <main class="main-container">
            <div class="form-container">
                <div class="header-container">
                    <h2>سیستم تولید تصویر با Freepik</h2>
                    <img src="{{ asset('images/freepik-logo-svgrepo-com.png') }}" alt="Freepik Logo" class="freepik-logo">
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
         <livewire:free-pick.show-image-modal />

        <div id="toast" class="toast">در حال تولید تصویر...</div>
    @endsection

    @section('script')
            <script src="{{asset('Assets/js/Freepick/app.js')}}"></script>
    @endsection
</div>
