document.addEventListener('DOMContentLoaded', () => {
    const themeSwitch = document.querySelector('.slider');
    const body = document.body;

    // Handle theme switch
    themeSwitch.addEventListener('click', () => {
        // Don't prevent default to allow checkbox state to change
        body.classList.toggle('dark-mode');
        
        // Save theme preference
        const isDark = body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    // Check saved theme
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        themeSwitch.checked = true;
    }
});