export default {
    install(app) {
        app.config.globalProperties.$admin = {
            /**
             * Generates a formatted price.
             *
             * @param {number} price - The price value to be formatted.
             * @returns {string} - The formatted price string.
             */
            formatPrice: (price) => {
                let locale = document.querySelector('meta[http-equiv="content-language"]').content;

                locale = locale.replace(/([a-z]{2})_([A-Z]{2})/g, '$1-$2');

                const currency = JSON.parse(document.querySelector('meta[name="currency"]').content);

                const symbol = currency.symbol !== '' ? currency.symbol : currency.code;

                if (! currency.currency_position) {
                    return new Intl.NumberFormat(locale, {
                        style: "currency",
                        currency: currency.code,
                    }).format(price);
                }

                const formatter = new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: currency.code,
                    minimumFractionDigits: currency.decimal ?? 2
                });

                const formattedCurrency = formatter.formatToParts(price)
                    .map(part => {
                        switch (part.type) {
                            case 'currency':
                                return '';

                            case 'group':
                                return currency.group_separator === ''
                                    ? part.value
                                    : currency.group_separator;

                            case 'decimal':
                                return currency.decimal_separator === ''
                                    ? part.value
                                    : currency.decimal_separator;

                            default:
                                return part.value;
                        }
                    })
                    .join('');

                switch (currency.currency_position) {
                    case 'left':
                        return symbol + formattedCurrency;

                    case 'left_with_space':
                        return symbol + ' ' + formattedCurrency;

                    case 'right':
                        return formattedCurrency + symbol;

                    case 'right_with_space':
                        return formattedCurrency + ' ' + symbol;

                    default:
                        return formattedCurrency;
                }
            },
            formatDate: (date) => {
                if (!date) return '';

                const d = new Date(date);
                const pad = (n) => n.toString().padStart(2, '0');

                // اليوم-الشهر-السنة
                const day = pad(d.getDate());
                const month = pad(d.getMonth() + 1);
                const year = d.getFullYear();

                // الساعة 12 ساعة + صباحًا / مساءً
                let hours = d.getHours();
                const ampm = hours >= 12 ? 'م' : 'ص';  // بالعربية
                hours = hours % 12;
                hours = hours ? hours : 12; // إذا 0 تصبح 12

                const minutes = pad(d.getMinutes());
                const seconds = pad(d.getSeconds());

                return `${day}-${month}-${year} ${pad(hours)}:${minutes}:${seconds} ${ampm}`;
            }
        };
    },
};
