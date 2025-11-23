import { defineConfig } from "vite";
import FullReload from "vite-plugin-full-reload";

export default defineConfig({
  root: ".",
  publicDir: "public",
  plugins: [FullReload(["**/*.php", "src/**/*.php"])], // ← reload când schimbi PHP
  build: {
    outDir: "assets/dist",
    assetsDir: "",
    emptyOutDir: true,
    cssCodeSplit: false,
    manifest: true,
    rollupOptions: {
      input: "src/main.js",
      output: { entryFileNames: "main.js", chunkFileNames: "vendor.js", assetFileNames: "main.[ext]" },
    },
  },
  server: {
    port: 5174, // folosim 5174
    strictPort: true, // dacă e ocupat, NU schimbă portul
    hmr: { host: "localhost", port: 5174 },
  },
});
