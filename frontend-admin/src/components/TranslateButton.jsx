import { useState } from 'react';
import client from '../api/client';
import toast from 'react-hot-toast';

/**
 * Auto-translate English fields to Tamil using the LibreTranslate proxy.
 *
 * Props:
 *   fields: Array of { sourceValue: string, onTranslated: (text: string) => void, label: string }
 *
 * Usage:
 *   <TranslateButton fields={[
 *     { sourceValue: form.title,       onTranslated: (v) => handleChange('title_ta', v),       label: 'Title' },
 *     { sourceValue: form.description, onTranslated: (v) => handleChange('description_ta', v), label: 'Description' },
 *   ]} />
 */
export default function TranslateButton({ fields = [] }) {
  const [loading, setLoading] = useState(false);

  const handleTranslate = async () => {
    const toTranslate = fields.filter((f) => f.sourceValue && f.sourceValue.trim().length > 0);

    if (toTranslate.length === 0) {
      toast.error('Fill English fields first before translating');
      return;
    }

    setLoading(true);
    let successCount = 0;
    let failCount = 0;

    await Promise.allSettled(
      toTranslate.map(async (f) => {
        try {
          const res = await client.post('/translate', { text: f.sourceValue.trim(), target: 'ta' });
          const translated = res.data?.translatedText || '';
          if (translated) {
            f.onTranslated(translated);
            successCount++;
          } else {
            failCount++;
          }
        } catch {
          failCount++;
        }
      })
    );

    setLoading(false);

    if (successCount > 0 && failCount === 0) {
      toast.success(`Translated ${successCount} field${successCount > 1 ? 's' : ''} to Tamil`);
    } else if (successCount > 0) {
      toast.success(`Translated ${successCount} field${successCount > 1 ? 's' : ''} (${failCount} failed)`);
    } else {
      toast.error('Translation failed — check LIBRETRANSLATE_URL in .env');
    }
  };

  return (
    <button
      type="button"
      className="btn-translate"
      onClick={handleTranslate}
      disabled={loading}
      title="Auto-translate English content to Tamil using LibreTranslate"
    >
      {loading ? (
        <span>⏳ Translating...</span>
      ) : (
        <span>🔄 Auto Translate to Tamil</span>
      )}
    </button>
  );
}
