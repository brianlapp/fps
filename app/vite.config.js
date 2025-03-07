import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {viteStaticCopy} from "vite-plugin-static-copy";
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', "resources/js/app_admin.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/images',
                    dest: '',
                }
            ],
            watch: {
                reloadPageOnChange: true
            }
        })
    ],
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
            }
        }
    }
});
