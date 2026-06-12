import { spawn } from 'child_process';
import process from 'process';

console.log("Starting Adonis client (Vite) and server (Laravel) concurrently...\n");
const phpBinary = process.env.PHP_BINARY || 'C:\\xampp\\php\\php.exe';

// Run Vite dev server (port 3000)
const client = spawn(process.platform === 'win32' ? 'npm.cmd' : 'npm', ['run', 'vite'], {
  stdio: 'inherit',
  shell: true
});

// Run Laravel server (port 8000)
const server = spawn(phpBinary, ['artisan', 'serve', '--host=127.0.0.1', '--port=8000'], {
  stdio: 'inherit',
  shell: true
});

// Handle termination
const cleanup = () => {
  console.log("\nTerminating processes...");
  try { client.kill(); } catch (e) {}
  try { server.kill(); } catch (e) {}
  process.exit();
};

process.on('SIGINT', cleanup);
process.on('SIGTERM', cleanup);

client.on('close', (code) => {
  console.log(`Vite client dev process exited with code ${code}`);
  cleanup();
});

server.on('close', (code) => {
  console.log(`Laravel server process exited with code ${code}`);
  cleanup();
});
