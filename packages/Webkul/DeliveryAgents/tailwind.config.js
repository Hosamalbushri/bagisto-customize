/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],


    theme: {

            extend: {
                colors: {
                    primary: {
                        light: '#dda15e',  // فاتح
                        DEFAULT: '#0044F2', // أساسي
                        dark: '#0038C9',    // غامق
                    },
                    secondary: {
                        DEFAULT: '#6B7280',
                        dark: '#4B5563',
                    },
                    accent: '#F85156', // لون مميز
                },


            },
    },






    plugins: [],
};
