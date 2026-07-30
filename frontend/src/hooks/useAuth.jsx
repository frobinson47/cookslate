import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import * as api from '../services/api';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    api.getMe()
      .then(data => {
        setUser(data.user || data);
      })
      .catch(() => {
        setUser(null);
      })
      .finally(() => {
        setIsLoading(false);
      });
  }, []);

  const login = useCallback(async (username, password) => {
    const data = await api.login(username, password);
    setUser(data.user || data);
    return data;
  }, []);

  const logout = useCallback(async () => {
    await api.logout();
    setUser(null);
  }, []);

  const isAdmin = user?.role === 'admin';
  const isViewer = user?.role === 'viewer';
  const isDemo = Boolean(user?.is_demo);

  return (
    <AuthContext.Provider value={{ user, login, logout, isAdmin, isViewer, isDemo, isLoading }}>
      {children}
    </AuthContext.Provider>
  );
}

// eslint-disable-next-line react-refresh/only-export-components -- hook co-located with its Provider by design
export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}

// eslint-disable-next-line react-refresh/only-export-components -- hook co-located with its Provider by design
export default useAuth;
