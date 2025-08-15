function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('darkMode', isDark);
    updateThemeButton(isDark);
}

// Update theme toggle button
function updateThemeButton(isDark) {
    const buttons = document.querySelectorAll('#themeToggle');
    buttons.forEach(button => {
        if (isDark) {
            button.querySelector('.fa-moon').classList.add('hidden');
            button.querySelector('.fa-sun').classList.remove('hidden');
        } else {
            button.querySelector('.fa-moon').classList.remove('hidden');
            button.querySelector('.fa-sun').classList.add('hidden');
        }
// No newline at end of file
    });
}

// Initialize dark mode based on preference
function initDarkMode() {
    // Check localStorage first, then system preference
    const storedPref = localStorage.getItem('darkMode');
    let isDark = false;
    
    if (storedPref !== null) {
       if (storedPref === 'true') document.documentElement.classList.add('dark');
        isDark = storedPref === 'true';
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        isDark = true;
    }

    if (isDark) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', true);
    }
    updateThemeButton(isDark);
    localStorage.setItem('darkMode', isDark);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', initDarkMode);
document.addEventListener('DOMContentLoaded', initDarkMode);

// Add event listener to theme toggle buttons
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('#themeToggle');
    buttons.forEach(button => {
        button.addEventListener('click', toggleDarkMode);
    });
});