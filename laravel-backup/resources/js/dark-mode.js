// Check for saved dark mode preference
const darkMode = localStorage.getItem('darkMode') === 'true';

// Apply initial dark mode state
if (darkMode) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

// Listen for dark mode toggle events
window.addEventListener('dark-mode-toggle', (event) => {
    const { darkMode } = event.detail;
    
    if (darkMode) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    }
}); 