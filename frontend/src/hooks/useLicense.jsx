import { useState, useEffect, createContext, useContext } from 'react';
import { getLicenseStatus } from '../services/api';

const LicenseContext = createContext({ active: false, tier: 'free', email: '', loading: true });

export function LicenseProvider({ children }) {
  const [license, setLicense] = useState({ active: false, tier: 'free', email: '', loading: true });

  useEffect(() => {
    getLicenseStatus()
      .then(data => setLicense({ ...data, loading: false }))
      .catch(() => setLicense(prev => ({ ...prev, loading: false })));
  }, []);

  const refresh = () => {
    getLicenseStatus()
      .then(data => setLicense({ ...data, loading: false }))
      .catch(() => {});
  };

  return (
    <LicenseContext.Provider value={{ ...license, refresh }}>
      {children}
    </LicenseContext.Provider>
  );
}

export function useLicense() {
  return useContext(LicenseContext);
}
