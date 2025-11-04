
 import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      // Forward all /api requests from Vite (http://localhost:5173) to the Symfony backend
      '/api': {
        target: process.env.BACKEND_ORIGIN || 'http://127.0.0.1:8000',
        changeOrigin: true,
        secure: false,
        // keep the /api path as-is
        rewrite: (path) => path,
      },
    },
  },
})
