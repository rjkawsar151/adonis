/**
 * Single-file WebP converter for uploads.
 * Usage: node scripts/webp-upload.mjs <inputPath> <outputPath> <quality> <maxDimension>
 * Reads any image (jpeg/png/webp/gif/tiff/avif) and writes a WebP file at the
 * requested quality (60-70 => roughly 60-70% compression). Used by the
 * ImageCompressor service as a fallback when PHP GD is unavailable.
 */

import sharp from 'sharp';

const [, , inPath, outPath, qualityArg, maxDimArg] = process.argv;

const quality = parseInt(qualityArg || '70', 10);
const maxDim = parseInt(maxDimArg || '1920', 10);

try {
  await sharp(inPath, { limitInputPixels: false })
    .rotate()
    .resize({ width: maxDim, height: maxDim, fit: 'inside', withoutEnlargement: true })
    .webp({ quality })
    .toFile(outPath);
  process.exit(0);
} catch (err) {
  console.error(err);
  process.exit(1);
}
