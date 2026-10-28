import laravel from "laravel-vite-plugin";
import legacy from "@vitejs/plugin-legacy";
import tailwindcss from "@tailwindcss/vite";

/** @type {import('vite').UserConfig} */
export default {
    // Relative URLs, so published assets work from any public path
    base: "./",
    // Load .env from the workbench application, e.g. APP_URL for the development server's CORS origins
    envDir: "workbench",
    plugins: [
        laravel({
            input: ["resources/js/package-name.js", "resources/css/package-name.css"],
            hotFile: "vite.hot",
            refresh: ["resources/views/**", "routes/**", "workbench/resources/views/**", "workbench/routes/**"],
        }),
        tailwindcss(),
        // TODO: Implement this in app.blade.php
        // legacy({
        //     targets: ["> 0.2%, last 2 versions, Firefox ESR, not dead"],
        // }),
    ],
    build: {
        outDir: "dist",
        assetsDir: "",
    },
};
