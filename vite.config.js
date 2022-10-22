import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    resolve: {
      extensions: ['.js', '.vue', '.json', '.css']
    },
    plugins: [
        vue(),
        laravel([
            'resources/css/*',
            'resources/js/*',
        ]),
    ],
});
