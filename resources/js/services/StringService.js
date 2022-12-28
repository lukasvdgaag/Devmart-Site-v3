export default {
    capFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },
    formatMoney(string) {
        return new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(string);
    },
    formatNumber(string) {
        return new Intl.NumberFormat('nl-NL').format(string);
    }
}
