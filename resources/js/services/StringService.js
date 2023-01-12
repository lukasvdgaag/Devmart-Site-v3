export default {
    capFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

    formatMoney(string, withSymbol = true) {
        // format the money with locale nl-NL and currency EUR and only include the symbol if withSymbol is true
        return new Intl.NumberFormat(withSymbol ? 'nl-NL' : 'fr-FR', {
            style: 'currency',
            currency: 'EUR',
            currencyDisplay: withSymbol ? 'symbol' : 'code'
        }).format(string);
    },

    formatNumber(string) {
        return new Intl.NumberFormat('nl-NL').format(string);
    },

    async getBase64(file) {
        // convert javascript file to base64 string
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    }
}
