/**
 * Image Optimization Script for Adonis Website
 * Converts all PNG images to WebP format with responsive sizes.
 * Run: node scripts/optimize-images.mjs
 */

import sharp from 'sharp';
import fs from 'fs';
import path from 'path';

const IMAGES_DIR = path.resolve('public/assets/images');
const OUTPUT_DIR = path.resolve('public/assets/images/optimized');

// Responsive breakpoints
const SIZES = [
  { suffix: '-400w', width: 400 },
  { suffix: '-800w', width: 800 },
  { suffix: '-1200w', width: 1200 },
];

// WebP quality
const WEBP_QUALITY = 80;
const LOGO_QUALITY = 90; // Higher quality for logo

async function ensureDir(dir) {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
}

async function optimizeImage(filePath, fileName) {
  const baseName = path.basename(fileName, path.extname(fileName));
  const isLogo = fileName.includes('logo');

  const quality = isLogo ? LOGO_QUALITY : WEBP_QUALITY;

  // Get original dimensions
  const metadata = await sharp(filePath).metadata();
  const origWidth = metadata.width || 1200;

  console.log(`  Processing: ${fileName} (${origWidth}x${metadata.height}, ${(fs.statSync(filePath).size / 1024).toFixed(0)} KB)`);

  // Generate full-size WebP
  const fullWebP = await sharp(filePath)
    .webp({ quality })
    .toBuffer();

  const fullPath = path.join(OUTPUT_DIR, `${baseName}.webp`);
  fs.writeFileSync(fullPath, fullWebP);
  console.log(`    ✓ Full WebP: ${(fullWebP.length / 1024).toFixed(0)} KB`);

  // Generate responsive sizes (only if image is larger than target)
  for (const size of SIZES) {
    if (origWidth > size.width) {
      const resized = await sharp(filePath)
        .resize(size.width, null, { withoutEnlargement: true })
        .webp({ quality })
        .toBuffer();

      const resizedPath = path.join(OUTPUT_DIR, `${baseName}${size.suffix}.webp`);
      fs.writeFileSync(resizedPath, resized);
      console.log(`    ✓ ${size.width}w WebP: ${(resized.length / 1024).toFixed(0)} KB`);
    }
  }

  // Keep a PNG fallback at reduced quality for older browsers (just the full size)
  const optimizedPng = await sharp(filePath)
    .resize(1200, null, { withoutEnlargement: true })
    .png({ quality: 80, compressionLevel: 9 })
    .toBuffer();
  const pngFallbackPath = path.join(OUTPUT_DIR, `${baseName}.png`);
  fs.writeFileSync(pngFallbackPath, optimizedPng);
  console.log(`    ✓ PNG fallback: ${(optimizedPng.length / 1024).toFixed(0)} KB`);
}

async function optimizeFavicon() {
  const faviconPath = path.resolve('public/favicon.ico');
  if (!fs.existsSync(faviconPath)) {
    console.log('  ⚠ No favicon.ico found');
    return;
  }

  const origSize = fs.statSync(faviconPath).size;
  console.log(`  Processing favicon.ico (${(origSize / 1024).toFixed(0)} KB)`);

  // Create optimized favicon as 32x32 and 16x16 PNG
  const favicon32 = await sharp(faviconPath)
    .resize(32, 32)
    .png({ quality: 80, compressionLevel: 9 })
    .toBuffer();

  const favicon16 = await sharp(faviconPath)
    .resize(16, 16)
    .png({ quality: 80, compressionLevel: 9 })
    .toBuffer();

  const favicon192 = await sharp(faviconPath)
    .resize(192, 192)
    .png({ quality: 85, compressionLevel: 9 })
    .toBuffer();

  fs.writeFileSync(path.resolve('public/favicon-32x32.png'), favicon32);
  fs.writeFileSync(path.resolve('public/favicon-16x16.png'), favicon16);
  fs.writeFileSync(path.resolve('public/apple-touch-icon.png'), favicon192);

  console.log(`    ✓ favicon-32x32.png: ${(favicon32.length / 1024).toFixed(1)} KB`);
  console.log(`    ✓ favicon-16x16.png: ${(favicon16.length / 1024).toFixed(1)} KB`);
  console.log(`    ✓ apple-touch-icon.png: ${(favicon192.length / 1024).toFixed(1)} KB`);
}

async function main() {
  console.log('🖼  Adonis Image Optimization Script');
  console.log('===================================\n');

  await ensureDir(OUTPUT_DIR);

  // Get all PNG files in images directory
  const files = fs.readdirSync(IMAGES_DIR)
    .filter(f => f.endsWith('.png') && !f.startsWith('.'));

  console.log(`Found ${files.length} PNG images to optimize.\n`);

  let totalOriginal = 0;
  let totalOptimized = 0;

  for (const file of files) {
    const filePath = path.join(IMAGES_DIR, file);
    const stat = fs.statSync(filePath);

    // Skip directories and the optimized folder
    if (stat.isDirectory()) continue;

    totalOriginal += stat.size;

    try {
      await optimizeImage(filePath, file);
    } catch (err) {
      console.error(`  ✗ Error processing ${file}:`, err.message);
    }
  }

  // Calculate optimized total
  const optimizedFiles = fs.readdirSync(OUTPUT_DIR);
  for (const file of optimizedFiles) {
    totalOptimized += fs.statSync(path.join(OUTPUT_DIR, file)).size;
  }

  console.log('\n--- Favicon Optimization ---');
  await optimizeFavicon();

  console.log('\n===================================');
  console.log(`Original total:  ${(totalOriginal / (1024 * 1024)).toFixed(2)} MB`);
  console.log(`Optimized total: ${(totalOptimized / (1024 * 1024)).toFixed(2)} MB`);
  console.log(`Reduction:       ${(100 - (totalOptimized / totalOriginal * 100)).toFixed(1)}%`);
  console.log('===================================\n');
  console.log('✅ Done! Optimized images are in: public/assets/images/optimized/');
}

main().catch(console.error);
