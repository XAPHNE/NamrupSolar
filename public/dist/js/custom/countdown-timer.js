// Get the target date from Blade (ensure it's formatted as YYYY-MM-DD HH:MM:SS)
// const targetDate = new Date("{{ $targetDate }}").getTime();

// Function to calculate the remaining time and update the display
function updateCountdown() {
    const now = new Date().getTime();
    const distance = targetDate - now;

    // Calculate time left in days, hours, minutes, seconds
    const years = Math.floor(distance / (1000 * 60 * 60 * 24 * 365));
    const months = Math.floor((distance % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24 * 30));
    const days = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Update the countdown timer display
    document.getElementById('countdown-timer').innerHTML =
        years + "Y " + months + "M " + days + "D " +
        hours + "H " + minutes + "M " + seconds + "S ";

    // If the countdown is over, display "EXPIRED"
    if (distance < 0) {
        clearInterval(countdownInterval);
        document.getElementById('countdown-timer').innerHTML = "EXPIRED";
    }
}

// Update the countdown every 1 second
const countdownInterval = setInterval(updateCountdown, 1000);