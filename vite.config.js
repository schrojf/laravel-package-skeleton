import legacy from "@vitejs/plugin-legacy";

/** @type {import('vite').UserConfig} */
export default {
    plugins: [
        // TODO: Implement this in app.blade.php
        // legacy({
        //     targets: ["defaults", "> 0.2% and not dead"],
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
