/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./src/**/*.php",
    "./vendor/salienture/**/*.php",
    "./vendor/saicosys/**/*.php",
    "./templates/**/*.{php,html,js}",
    "./webroot/js/**/*.js",
    "./webroot/css/**/*.css",
  ],
  theme: {
    extend: {
      colors: {
        background: 'rgb(var(--background) / <alpha-value>)',
        foreground: 'rgb(var(--foreground) / <alpha-value>)',
        card: {
          DEFAULT: 'rgb(var(--card) / <alpha-value>)',
          foreground: 'rgb(var(--card-foreground) / <alpha-value>)',
        },
        popover: {
          DEFAULT: 'rgb(var(--popover) / <alpha-value>)',
          foreground: 'rgb(var(--popover-foreground) / <alpha-value>)',
        },
        primary: {
          DEFAULT: 'rgb(var(--primary) / <alpha-value>)',
          foreground: 'rgb(var(--primary-foreground) / <alpha-value>)',
        },
        secondary: {
          DEFAULT: 'rgb(var(--secondary) / <alpha-value>)',
          foreground: 'rgb(var(--secondary-foreground) / <alpha-value>)',
        },
        muted: {
          DEFAULT: 'rgb(var(--muted) / <alpha-value>)',
          foreground: 'rgb(var(--muted-foreground) / <alpha-value>)',
        },
        accent: {
          DEFAULT: 'rgb(var(--accent) / <alpha-value>)',
          foreground: 'rgb(var(--accent-foreground) / <alpha-value>)',
        },
        destructive: {
          DEFAULT: 'rgb(var(--destructive) / <alpha-value>)',
          foreground: 'rgb(var(--destructive-foreground) / <alpha-value>)',
        },
        border: 'rgb(var(--border) / <alpha-value>)',
        input: 'rgb(var(--input) / <alpha-value>)',
        ring: 'rgb(var(--ring) / <alpha-value>)',
        sidebar: {
          DEFAULT: 'rgb(var(--sidebar) / <alpha-value>)',
          foreground: 'rgb(var(--sidebar-foreground) / <alpha-value>)',
        },
        shadow: 'rgb(var(--shadow) / <alpha-value>)',
      },
      borderRadius: {
        lg: 'var(--radius)',
        md: 'calc(var(--radius) - 2px)',
        sm: 'calc(var(--radius) - 4px)',
      },
      boxShadow: {
        DEFAULT: '0 1px 3px 0 rgb(var(--shadow) / 0.1), 0 1px 2px -1px rgb(var(--shadow) / 0.1)',
        sm: '0 1px 2px 0 rgb(var(--shadow) / 0.05)',
        md: '0 4px 6px -1px rgb(var(--shadow) / 0.1), 0 2px 4px -2px rgb(var(--shadow) / 0.1)',
        lg: '0 10px 15px -3px rgb(var(--shadow) / 0.1), 0 4px 6px -4px rgb(var(--shadow) / 0.1)',
        xl: '0 20px 25px -5px rgb(var(--shadow) / 0.1), 0 8px 10px -6px rgb(var(--shadow) / 0.1)',
        '2xl': '0 25px 50px -12px rgb(var(--shadow) / 0.25)',
      },
    },
  },
  plugins: [],
}
