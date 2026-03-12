{{-- Preloader overlay component --}}
<div id="preloader" aria-live="polite" aria-label="Loading">
    <div class="preloader-card">
        <div class="preloader-ring"></div>
        <p class="preloader-name">ahmed - laravel</p>
    </div>
</div>

{{-- Component styles --}}
<style>
    #preloader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 20% 20%, rgba(14, 165, 233, 0.18) 0%, transparent 40%),
            radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.14) 0%, transparent 40%),
            #f7fafc;
        transition: opacity 0.35s ease, visibility 0.35s ease;
    }

    #preloader.hide {
        opacity: 0;
        visibility: hidden;
    }

    .preloader-card {
        min-width: 190px;
        padding: 22px 26px;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 18px 44px rgba(15, 23, 42, 0.14);
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
    }

    .preloader-ring {
        width: 62px;
        height: 62px;
        margin: 0 auto 14px;
        border-radius: 999px;
        border: 4px solid rgba(14, 165, 233, 0.22);
        border-top-color: #0ea5e9;
        border-right-color: #10b981;
        animation: preloader-spin 0.9s linear infinite;
    }

    .preloader-name {
        margin: 0;
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    @keyframes preloader-spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

{{-- Component script --}}
<script>
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');

        if (!preloader) {
            return;
        }

        preloader.classList.add('hide');

        setTimeout(function() {
            preloader.remove();
        }, 350);
    });
</script>
