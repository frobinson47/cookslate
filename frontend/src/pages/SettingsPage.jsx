import { useState, useEffect } from 'react';
import { KeyRound, Check, X, LogOut, Camera } from 'lucide-react';
import { useLicense } from '../hooks/useLicense';
import { useAuth } from '../hooks/useAuth';
import { activateLicense, deactivateLicense, getOpenAiKeyStatus, saveOpenAiKey, deleteOpenAiKey } from '../services/api';
import Input from '../components/ui/Input';

export default function SettingsPage() {
  const { active, tier, email, refresh } = useLicense();
  const { user, logout } = useAuth();
  const [key, setKey] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const [openAiConfigured, setOpenAiConfigured] = useState(false);
  const [openAiKeyInput, setOpenAiKeyInput] = useState('');
  const [openAiLoading, setOpenAiLoading] = useState(false);
  const [openAiError, setOpenAiError] = useState('');
  const [openAiSuccess, setOpenAiSuccess] = useState('');

  useEffect(() => {
    getOpenAiKeyStatus()
      .then((result) => setOpenAiConfigured(!!result.configured))
      .catch(() => {});
  }, []);

  const handleActivate = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);
    try {
      const result = await activateLicense(key.trim());
      setSuccess(result.message);
      setKey('');
      refresh();
    } catch (err) {
      setError(err.message || 'Invalid license key');
    } finally {
      setLoading(false);
    }
  };

  const handleDeactivate = async () => {
    setLoading(true);
    try {
      await deactivateLicense();
      setSuccess('License deactivated');
      refresh();
    } catch (err) {
      setError(err.message || 'Failed to deactivate');
    } finally {
      setLoading(false);
    }
  };

  const handleSaveOpenAiKey = async (e) => {
    e.preventDefault();
    setOpenAiError('');
    setOpenAiSuccess('');
    setOpenAiLoading(true);
    try {
      await saveOpenAiKey(openAiKeyInput.trim());
      setOpenAiConfigured(true);
      setOpenAiKeyInput('');
      setOpenAiSuccess('OpenAI key connected');
    } catch (err) {
      setOpenAiError(err.message || 'Failed to save key');
    } finally {
      setOpenAiLoading(false);
    }
  };

  const handleRemoveOpenAiKey = async () => {
    setOpenAiLoading(true);
    try {
      await deleteOpenAiKey();
      setOpenAiConfigured(false);
      setOpenAiSuccess('OpenAI key removed');
    } catch (err) {
      setOpenAiError(err.message || 'Failed to remove key');
    } finally {
      setOpenAiLoading(false);
    }
  };

  return (
    <div className="max-w-2xl mx-auto">
      <h1 className="text-2xl font-bold font-display text-brown mb-6">Settings</h1>

      <div className="bg-surface rounded-2xl p-6 shadow-md border border-cream-dark">
        <div className="flex items-center gap-3 mb-4">
          <KeyRound className="w-5 h-5 text-terracotta" />
          <h2 className="text-lg font-semibold text-brown">License</h2>
        </div>

        {active ? (
          <div>
            <div className="flex items-center gap-2 text-sage mb-2">
              <Check className="w-4 h-4" />
              <span className="font-medium">Cookslate Pro — Active</span>
            </div>
            <p className="text-sm text-warm-gray mb-4">Licensed to {email}</p>
            <button
              onClick={handleDeactivate}
              disabled={loading}
              className="text-sm text-warm-gray hover:text-red-500 transition-colors"
            >
              Deactivate license
            </button>
          </div>
        ) : (
          <div>
            <p className="text-sm text-brown-light mb-4">
              Enter your license key to unlock Pro features: meal planning, cook stats, annotations, multi-user support, and more.
            </p>
            <form onSubmit={handleActivate} className="flex gap-2">
              <Input
                type="text"
                value={key}
                onChange={(e) => setKey(e.target.value)}
                placeholder="Paste your license key"
              />
              <button
                type="submit"
                disabled={loading || !key.trim()}
                className="px-4 py-2 text-sm font-medium text-white bg-terracotta rounded-lg hover:bg-terracotta/90 disabled:opacity-50"
              >
                Activate
              </button>
            </form>
          </div>
        )}

        {error && (
          <div className="mt-3 flex items-center gap-2 text-red-500 text-sm">
            <X className="w-4 h-4" />
            {error}
          </div>
        )}
        {success && (
          <div className="mt-3 flex items-center gap-2 text-green-600 text-sm">
            <Check className="w-4 h-4" />
            {success}
          </div>
        )}
      </div>

      <p className="mt-4 text-center text-sm text-warm-400">
        <a href="https://cookslate.app" target="_blank" rel="noopener noreferrer" className="hover:text-terracotta transition-colors">
          Get a license at cookslate.app
        </a>
      </p>

      {/* OpenAI key section */}
      <div className="mt-6 bg-surface rounded-2xl p-6 shadow-md border border-cream-dark">
        <div className="flex items-center gap-3 mb-4">
          <Camera className="w-5 h-5 text-terracotta" />
          <h2 className="text-lg font-semibold text-brown">OpenAI API Key</h2>
        </div>

        {openAiConfigured ? (
          <div>
            <div className="flex items-center gap-2 text-sage mb-2">
              <Check className="w-4 h-4" />
              <span className="font-medium">Connected</span>
            </div>
            <p className="text-sm text-warm-gray mb-4">Used for Import from Photo.</p>
            <button
              onClick={handleRemoveOpenAiKey}
              disabled={openAiLoading}
              className="text-sm text-warm-gray hover:text-red-500 transition-colors"
            >
              Remove key
            </button>
          </div>
        ) : (
          <div>
            <p className="text-sm text-brown-light mb-4">
              Add your own OpenAI API key to import recipes from photos (cookbook pages, recipe cards, screenshots).
              Get a key at <a href="https://platform.openai.com/api-keys" target="_blank" rel="noopener noreferrer" className="text-terracotta hover:underline">platform.openai.com/api-keys</a>.
              Stored encrypted; never shown again after saving.
            </p>
            <form onSubmit={handleSaveOpenAiKey} className="flex gap-2">
              <Input
                type="password"
                value={openAiKeyInput}
                onChange={(e) => setOpenAiKeyInput(e.target.value)}
                placeholder="sk-..."
              />
              <button
                type="submit"
                disabled={openAiLoading || !openAiKeyInput.trim()}
                className="px-4 py-2 text-sm font-medium text-white bg-terracotta rounded-lg hover:bg-terracotta/90 disabled:opacity-50"
              >
                Save
              </button>
            </form>
          </div>
        )}

        {openAiError && (
          <div className="mt-3 flex items-center gap-2 text-red-500 text-sm">
            <X className="w-4 h-4" />
            {openAiError}
          </div>
        )}
        {openAiSuccess && (
          <div className="mt-3 flex items-center gap-2 text-green-600 text-sm">
            <Check className="w-4 h-4" />
            {openAiSuccess}
          </div>
        )}
      </div>

      {/* Account section */}
      <div className="mt-6 bg-surface rounded-2xl p-6 shadow-md border border-cream-dark">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-sm font-semibold text-brown">{user?.username}</p>
            <p className="text-xs text-warm-gray capitalize">{user?.role}</p>
          </div>
          <button
            onClick={async () => { try { await logout(); } catch {} }}
            className="flex items-center gap-2 px-4 py-2.5 rounded-xl text-brown-light hover:bg-red-50 hover:text-red-600 transition-colors duration-200 font-medium"
          >
            <LogOut size={18} />
            <span>Log Out</span>
          </button>
        </div>
      </div>
    </div>
  );
}
