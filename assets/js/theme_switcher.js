document.addEventListener('DOMContentLoaded', () => {
    const themeSelect = document.getElementById('theme-select');
    let savedTheme = localStorage.getItem('theme') || 'teal';

    // Force migration from purple to teal if needed
    if (savedTheme === 'purple') {
        savedTheme = 'teal';
        localStorage.setItem('theme', 'teal');
    }

    // Apply saved theme
    document.documentElement.setAttribute('data-theme', savedTheme);
    if (themeSelect) themeSelect.value = savedTheme;

    if (themeSelect) {
        themeSelect.addEventListener('change', (e) => {
            const theme = e.target.value;
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });
    }
});
