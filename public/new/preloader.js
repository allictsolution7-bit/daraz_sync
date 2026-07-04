
    // Improved Preloader functionality - Fixed 1 second duration
document.addEventListener('DOMContentLoaded', function () {
    // Disable scrolling while preloader is active
    document.body.style.overflow = 'hidden';

    // Get all clothing items
    const clothingItems = document.querySelectorAll('.clothing-item');
    let currentIndex = 0;

    // Function to show next icon
    function showNextIcon() {
        // Hide current active icon
        clothingItems.forEach(item => {
            item.classList.remove('active');
        });

        // Show next icon
        clothingItems[currentIndex].classList.add('active');

        // Update index for next time
        currentIndex = (currentIndex + 1) % clothingItems.length;
    }

    // Start cycling through icons
    showNextIcon(); // Show first icon immediately
    const iconInterval = setInterval(showNextIcon, 400); // Change icon every 400ms for faster animation

    // Set a fixed timeout of 2 seconds for the preloader
    setTimeout(function () {
        const preloader = document.querySelector('.preloader');
        
        // Clear the icon interval
        clearInterval(iconInterval);
        
        // Add fade-out class
        preloader.classList.add('fade-out');
        
        // Hide preloader after fade animation
        setTimeout(function () {
            preloader.style.display = 'none';
            document.body.style.overflow = ''; // Re-enable scrolling
        }, 150);
    }, 700); // Fixed 2 second duration
});
    
    // // Improved Preloader functionality - Single Icon at a Time
    // document.addEventListener('DOMContentLoaded', function () {
    //     // Disable scrolling while preloader is active
    //     document.body.style.overflow = 'hidden';

    //     // Get all clothing items
    //     const clothingItems = document.querySelectorAll('.clothing-item');
    //     let currentIndex = 0;

    //     // Function to show next icon
    //     function showNextIcon() {
    //         // Hide current active icon
    //         clothingItems.forEach(item => {
    //             item.classList.remove('active');
    //         });

    //         // Show next icon
    //         clothingItems[currentIndex].classList.add('active');

    //         // Update index for next time
    //         currentIndex = (currentIndex + 1) % clothingItems.length;
    //     }

    //     // Start cycling through icons
    //     showNextIcon(); // Show first icon immediately
    //     const iconInterval = setInterval(showNextIcon, 800); // Change icon every 800ms

    //     // When everything is loaded
    //     window.addEventListener('load', function () {
    //         const preloader = document.querySelector('.preloader');

    //         // Clear the icon interval
    //         clearInterval(iconInterval);

    //         // Shorter delay for faster transition
    //         setTimeout(function () {
    //             preloader.classList.add('fade-out');

    //             // Faster fade-out animation
    //             setTimeout(function () {
    //                 preloader.style.display = 'none';
    //                 document.body.style.overflow = '';
    //             }, 300);
    //         }, 800); // Show preloader for less time
    //     });
    // });