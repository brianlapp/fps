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
    },
    build: {
        // Improve build performance and handle external dependencies
        commonjsOptions: {
            include: [/node_modules/],
            transformMixedEsModules: true
        },
        rollupOptions: {
            // External packages that shouldn't be bundled
            external: [],
            // Properly handle packages with nested dependencies
            output: {
                manualChunks: {
                    'codemirror-bundle': ['codemirror', '@codemirror/lang-html', '@codemirror/lang-javascript', '@codemirror/lang-json', '@codemirror/theme-one-dark'],
                    'vue-libs': ['vue', '@vueuse/core', 'vue-codemirror'],
                    'ui-libs': ['flowbite', 'aos', 'vue-multiselect', 'vue3-tags-input']
                }
            }
        },
        // Increase the warning limit to avoid unnecessary warnings
        chunkSizeWarningLimit: 1500
    }
});
