import { createContext, useContext, useState, useCallback } from 'react';
import en from '../lang/en.json';
import ta from '../lang/ta.json';

const strings = { en, ta };

const LangContext = createContext(null);

export function LangProvider({ children }) {
  const [locale, setLocale] = useState(() => localStorage.getItem('admin_lang') || 'en');

  const toggle = useCallback(() => {
    setLocale((prev) => {
      const next = prev === 'en' ? 'ta' : 'en';
      localStorage.setItem('admin_lang', next);
      return next;
    });
  }, []);

  /**
   * t('sidebar.dashboard')  → looks up strings[locale].sidebar.dashboard
   * Supports dot-notation paths up to 2 levels deep.
   */
  const t = useCallback((key) => {
    const parts = key.split('.');
    const dict = strings[locale] || strings.en;
    let val = dict;
    for (const part of parts) {
      val = val?.[part];
      if (val === undefined) break;
    }
    if (typeof val === 'string') return val;
    // fallback to English
    val = strings.en;
    for (const part of parts) {
      val = val?.[part];
    }
    return typeof val === 'string' ? val : key;
  }, [locale]);

  return (
    <LangContext.Provider value={{ locale, toggle, t }}>
      {children}
    </LangContext.Provider>
  );
}

export function useLang() {
  const ctx = useContext(LangContext);
  if (!ctx) throw new Error('useLang must be used inside LangProvider');
  return ctx;
}
