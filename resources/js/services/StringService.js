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
    },

    formatFileSize(bytes, decimals = 2) {
        // format the file size in bytes to a readable format
        if (bytes === 0) return '0 B';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    },

    getWikiSidebarItemName(route) {
        const name = route.path.split('/').pop().replaceAll(/[-.]/g, ' ');
        return route.meta?.name ?? (name === '' ? 'Introduction' : name);
    }
}
