import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import autoprefixer from "autoprefixer";
import tailwindcss from "tailwindcss";

export default defineConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json', '.css']
    },
    css: {
      postcss: {
          plugins: [
              tailwindcss(),
              autoprefixer()
          ]
      }
    },

    plugins: [
        tailwindcss,
        autoprefixer,
        vue(),
        laravel({
            input: [
                'resources/css/*',
                'resources/js/*',
            ]
        },),
    ],

});
