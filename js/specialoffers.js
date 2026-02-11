document.addEventListener('DOMContentLoaded', function() {
    const offerCards = document.querySelectorAll('.offer-card');
    
    offerCards.forEach(card => {
        //Click to expand details
        card.addEventListener('click', function() {
            this.classList.toggle('expanded');
        });

        //Favorite button
        const favoriteBtn = document.createElement('button');
        favoriteBtn.className = 'favorite-btn';
        favoriteBtn.innerHTML = '❤';
        favoriteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });
        card.querySelector('.offer-details').appendChild(favoriteBtn);
    });

    //Countdown timer for limited offers
    function updateCountdown() {
        const countdownElements = document.querySelectorAll('.countdown');
        const now = new Date();
        const endDate = new Date();
        endDate.setDate(now.getDate() + 3); // Offer ends in 3 days
        
        const diff = endDate - now;
        
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        countdownElements.forEach(el => {
            el.textContent = `Offer ends in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
        });
    }
    
    // Update countdown every second
    setInterval(updateCountdown, 1000);
    updateCountdown();
});
