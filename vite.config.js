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
        // Vite 6 defaults, so the CSS minifier doesn't emit syntax unsupported by the browsers in postcss.config.js
        cssTarget: ["chrome87", "edge88", "firefox78", "safari14"],
        rolldownOptions: {
            input: ["resources/js/package-name.js", "resources/css/package-name.css"],
            output: {
                assetFileNames: "[name][extname]",
                entryFileNames: "[name].js",
            },
        },
    },
};
