/*
|--------------------------------------------------------------------------
| CoreERP Session Timeout
|--------------------------------------------------------------------------
|
| 600 detik/10 menit tidak ada aktivitas
| -> tampilkan modal
|
| 15 detik hitung mundur kemudian
| -> logout otomatis
|
*/

document.addEventListener("DOMContentLoaded", () => {

    const WARNING_AFTER = 600;      // detik
    const COUNTDOWN = 15;          // detik

    let idleTimer = null;
    let countdownTimer = null;
    let secondsLeft = COUNTDOWN;

    const modal = document.getElementById("sessionLock");
    const counter = document.getElementById("lockCounter");
    const stayButton = document.getElementById("stayLoggedIn");
    const logoutForm = document.getElementById("autoLogoutForm");

    if (!modal || !counter || !logoutForm) {
        return;
    }

    function closeModal() {
        modal.style.display = "none";
        clearInterval(countdownTimer);
        secondsLeft = COUNTDOWN;
    }

    function logoutNow() {
        logoutForm.submit();
    }

    function startCountdown() {

        modal.style.display = "flex";

        counter.innerText = secondsLeft;

        countdownTimer = setInterval(() => {

            secondsLeft--;

            counter.innerText = secondsLeft;

            if (secondsLeft <= 0) {

                clearInterval(countdownTimer);

                logoutNow();

            }

        }, 1000);

    }

    function resetIdleTimer() {

        clearTimeout(idleTimer);

        closeModal();

        idleTimer = setTimeout(() => {

            startCountdown();

        }, WARNING_AFTER * 1000);

    }

    [
        "mousemove",
        "mousedown",
        "click",
        "keydown",
        "scroll",
        "touchstart"
    ].forEach(event => {

        document.addEventListener(event, resetIdleTimer, true);

    });

    if (stayButton) {

        stayButton.addEventListener("click", resetIdleTimer);

    }

    resetIdleTimer();

});