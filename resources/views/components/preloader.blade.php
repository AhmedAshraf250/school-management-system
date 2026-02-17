{{-- ========== PRELOADER OVERLAY ========== --}}
<div id="preloader">
    <div class="preloader-inner">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
        <p class="preloader-text">{{ config('app.name') }}</p>
    </div>
</div>

{{-- ========== STYLES ========== --}}
<style>
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    #preloader.hide {
        opacity: 0;
        visibility: hidden;
    }

    .spinner {
        width: 60px;
        height: 60px;
        position: relative;
        margin: 0 auto 16px;
    }

    .double-bounce1,
    .double-bounce2 {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #4f46e5;
        /* غيّر اللون حسب موقعك */
        opacity: 0.6;
        position: absolute;
        top: 0;
        left: 0;
        animation: bounce 2s infinite ease-in-out;
    }

    .double-bounce2 {
        animation-delay: -1s;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: scale(0);
        }

        50% {
            transform: scale(1);
        }
    }

    .preloader-text {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 0;
        animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }
</style>

{{-- ========== SCRIPT ========== --}}
<script>
    // لما الصفحة تخلص تحميل خالص، اخفي الـ preloader
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.classList.add('hide');
            // بعد انتهاء الـ transition شيله من الـ DOM
            setTimeout(() => preloader.remove(), 500);
        }
    });
</script>
