import { ref, onMounted } from 'vue'

// Global theme state - shared across all components
const isDark = ref(false)

const updateTheme = (dark) => {
    isDark.value = dark;
    document.documentElement.classList.add('disable-transitions');
    if (dark) {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark'
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light'
    }

    setTimeout(() => {
       document.documentElement.classList.remove('disable-transitions');
    }, 0);
}

const toggleTheme = () => {
    updateTheme(!isDark.value)
}

// Initialize theme once
let initialized = false

export function useTheme() {
    onMounted(() => {
        if (!initialized) {
            initialized = true
            
            // Check initial theme
            isDark.value = 
                localStorage.theme === 'dark' || 
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
            
            updateTheme(isDark.value)

            // Optional: Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!('theme' in localStorage)) {
                    updateTheme(e.matches)
                }
            })
        }
    })

    return {
        isDark,
        toggleTheme,
        updateTheme
    }
}