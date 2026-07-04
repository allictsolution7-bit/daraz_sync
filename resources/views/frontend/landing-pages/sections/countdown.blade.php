<style>
    .countdown-section {
        background: var(--primary-color);
        padding: 40px 20px;
        border-radius: 0px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        width: 100%;
        margin: 0 auto;
    }

    .countdown-title {
        color: white;
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .countdown-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .time-box {
        background: rgba(255, 255, 255, 0.1);
        border: 3px solid rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        padding: 5px;
        min-width: 120px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .time-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .time-number {
        color: white;
        font-size: 48px;
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .time-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 16px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .countdown-section {
            width: 100%;
            padding: 25px 12px;
            border-radius: 0px;
        }

        .countdown-title {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .time-box {
            min-width: 100px;
            padding: 15px;
        }

        .time-number {
            font-size: 36px;
            margin-bottom: 0px;
        }

        .time-label {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .countdown-container {
            gap: 15px;
        }

        .time-box {
            min-width: 80px;
            padding: 0px;
        }

        .time-number {
            font-size: 30px;
        }

        .time-label {
            font-size: 12px;
        }
    }
</style>

@if ($section && $section->status)
<section class="countdown-section">
    <h2 class="countdown-title">{{ $section->countdown_title ?? 'Offer ends soon!' }}</h2>
    <div class="countdown-container">
        <div class="time-box">
            <span class="time-number" id="hours-{{ $section->id }}">০০</span>
            <span class="time-label">ঘন্টা</span>
        </div>
        <div class="time-box">
            <span class="time-number" id="minutes-{{ $section->id }}">০০</span>
            <span class="time-label">মিনিট</span>
        </div>
        <div class="time-box">
            <span class="time-number" id="seconds-{{ $section->id }}">০০</span>
            <span class="time-label">সেকেন্ড</span>
        </div>
    </div>
</section>
<script>
    (function() {
        function toBanglaNumber(num) {
            const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return num.toString().split('').map(digit => banglaNumbers[parseInt(digit)] || digit).join('');
        }

        let countdownHours = {{ $section->countdown_hours ?? 4 }};
        let repeat = {{ $section->countdown_repeat ? 'true' : 'false' }};
        let storageKey = 'countdown_end_{{ $section->id }}';
        let settingsKey = 'countdown_settings_{{ $section->id }}';
        let targetDate;

        let currentSettings = JSON.stringify({ hours: countdownHours, repeat: repeat });
        let storedSettings = localStorage.getItem(settingsKey);
        let settingsChanged = storedSettings !== currentSettings;
        let storedEndTime = localStorage.getItem(storageKey);

        if (storedEndTime && !settingsChanged) {
            targetDate = parseInt(storedEndTime);
            if (targetDate < new Date().getTime() && repeat) {
                targetDate = new Date().getTime() + countdownHours * 60 * 60 * 1000;
                localStorage.setItem(storageKey, targetDate.toString());
            }
        } else {
            targetDate = new Date().getTime() + countdownHours * 60 * 60 * 1000;
            localStorage.setItem(storageKey, targetDate.toString());
            localStorage.setItem(settingsKey, currentSettings);
        }

        function updateCountdown() {
            const now = new Date().getTime();
            let distance = targetDate - now;

            if (distance > 0) {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('hours-{{ $section->id }}').textContent = toBanglaNumber(hours.toString().padStart(2, '0'));
                document.getElementById('minutes-{{ $section->id }}').textContent = toBanglaNumber(minutes.toString().padStart(2, '0'));
                document.getElementById('seconds-{{ $section->id }}').textContent = toBanglaNumber(seconds.toString().padStart(2, '0'));
            } else {
                document.getElementById('hours-{{ $section->id }}').textContent = '০০';
                document.getElementById('minutes-{{ $section->id }}').textContent = '০০';
                document.getElementById('seconds-{{ $section->id }}').textContent = '০০';

                if (repeat) {
                    targetDate = new Date().getTime() + countdownHours * 60 * 60 * 1000;
                    localStorage.setItem(storageKey, targetDate.toString());
                } else {
                    localStorage.removeItem(storageKey);
                }
            }
        }
        setInterval(updateCountdown, 1000);
    })();
</script>
@endif
