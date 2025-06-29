import legacy from "@vitejs/plugin-legacy";

/** @type {import('vite').UserConfig} */
export default {
    plugins: [
        // TODO: Implement this in app.blade.php
        // legacy({
        //     targets: ["> 0.2%, last 2 versions, Firefox ESR, not dead"],
        // }),
    ],
    build: {
        assetsDir: "",
        rollupOptions: {
            input: ["resources/js/package-name.js", "resources/css/package-name.css"],
            output: {
                assetFileNames: "[name][extname]",
                entryFileNames: "[name].js",
            },
        },
    },
};
