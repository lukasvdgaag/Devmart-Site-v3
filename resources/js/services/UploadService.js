export default {

    /**
     * Returns a resized image from a base64 string.
     * {string} @param data
     */
    async resizeImage(data, maxWidth, maxHeight) {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.src = data;
            image.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                let width = image.width;
                let height = image.height;

                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(image, 0, 0, width, height);

                resolve(canvas.toDataURL());
            };
        });
    }
}
