import { describe, expect, it } from 'vitest';

import { formatFileSize } from './bytes';

describe('bytes helper test (formatFileSize, bytes.ts)', () => {
    it('should return "0 B" when bytes is 0', () => {
        expect(formatFileSize(0)).toBe('0 B');
    });

    it('should correctly format sizes in bytes (B)', () => {
        expect(formatFileSize(500)).toBe('500 B');
    });

    it('should correctly format sizes in kilobytes (KB)', () => {
        expect(formatFileSize(1024)).toBe('1 KB');
        expect(formatFileSize(1500)).toBe('1.46 KB');
    });

    it('should correctly format sizes in megabytes (MB)', () => {
        expect(formatFileSize(1048576)).toBe('1 MB');
        expect(formatFileSize(1572864)).toBe('1.5 MB');
    });

    it('should correctly format sizes in gigabytes (GB)', () => {
        expect(formatFileSize(1073741824)).toBe('1 GB');
        expect(formatFileSize(3221225472)).toBe('3 GB');
    });

    it('should correctly format sizes in terabytes (TB)', () => {
        expect(formatFileSize(1099511627776)).toBe('1 TB');
    });

    it('should correctly format sizes in petabytes (PB)', () => {
        expect(formatFileSize(1125899906842624)).toBe('1 PB');
    });

    it('should handle very large file sizes', () => {
        expect(formatFileSize(1152921504606846976)).toBe('1 EB');
    });

    it('should round to two decimal places', () => {
        expect(formatFileSize(123456789)).toBe('117.74 MB');
    });
});
