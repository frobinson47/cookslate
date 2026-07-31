import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { AlertCircle, Eye, Github, Shield } from 'lucide-react';
import CookslateLogo from '../components/ui/CookslateLogo';
import { useAuth } from '../hooks/useAuth';
import Button from '../components/ui/Button';
import Input from '../components/ui/Input';
import * as api from '../services/api';
import useDocumentTitle from '../hooks/useDocumentTitle';

export default function LoginPage() {
  useDocumentTitle('Sign In');

  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [ssoEnabled, setSsoEnabled] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();

  // Purely decorative — crossfades the brand panel's background photo. No data dependency.
  const heroImages = [
    '/images/login/hero-1.jpg',
    '/images/login/hero-2.jpg',
    '/images/login/hero-3.jpg',
    '/images/login/hero-4.jpg',
    '/images/login/hero-5.jpg',
    '/images/login/hero-6.jpg',
  ];
  const [heroIndex, setHeroIndex] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setHeroIndex((i) => (i + 1) % heroImages.length);
    }, 5000);
    return () => clearInterval(interval);
  }, [heroImages.length]);

  useEffect(() => {
    // Check if app needs initial setup (install wizard)
    fetch('/api/install.php')
      .then(r => {
        if (r.ok) {
          // Install endpoint is accessible and returned requirements — needs setup
          navigate('/install', { replace: true });
        }
        // 403 = already installed, continue to login
      })
      .catch(() => {
        // Install endpoint not available, continue to login
      });

    // Check if SSO is configured
    api.getSsoConfig()
      .then(data => setSsoEnabled(data.enabled))
      .catch(() => setSsoEnabled(false));

    // Check for OAuth error in URL params
    const oauthError = searchParams.get('error');
    if (oauthError) {
      setError(oauthError);
    }
  }, [searchParams, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setIsSubmitting(true);

    try {
      await login(username, password);
      navigate('/');
    } catch (err) {
      setError(err.message || 'Login failed. Please check your credentials.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleDemo = async () => {
    setError(null);
    setIsSubmitting(true);
    try {
      await login('demo', 'demo');
      navigate('/');
    } catch (err) {
      setError(err.message || 'Demo login failed.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen flex flex-col md:flex-row">
      {/* ── Brand panel — rotating hero photography with a color-tint overlay ── */}
      <div className="relative overflow-hidden flex flex-col items-center justify-center text-center px-8 py-16 md:py-8 md:w-[45%] min-h-[38vh] md:min-h-screen">
        {/* Rotating background photos — purely decorative, crossfades every 5s */}
        <div aria-hidden="true" className="absolute inset-0">
          {heroImages.map((src, i) => (
            <img
              key={src}
              src={src}
              alt=""
              className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-[1500ms] ease-out ${
                i === heroIndex ? 'opacity-100' : 'opacity-0'
              }`}
            />
          ))}
        </div>

        {/* Color-tint overlay — keeps the wordmark and text legible over any photo */}
        <div
          aria-hidden="true"
          className="pointer-events-none absolute inset-0"
          style={{ background: 'linear-gradient(160deg, rgba(193,105,79,0.82) 0%, rgba(168,83,58,0.85) 55%, rgba(95,122,88,0.88) 100%)' }}
        />

        <div className="relative motion-safe:animate-[login-fade-up_0.7s_var(--ease-emphasize)_both]">
          <div className="relative inline-flex items-center justify-center mb-6 motion-safe:animate-[login-pop_0.7s_var(--ease-spring)_both]">
            <CookslateLogo size={88} className="relative text-terracotta drop-shadow-xl" />
          </div>
          <h1 className="text-5xl md:text-6xl font-bold text-white font-serif tracking-tight drop-shadow-md">
            Cookslate
          </h1>
          <p className="text-white/90 text-lg font-medium mt-3 max-w-xs mx-auto">
            Your cozy recipe manager
          </p>
          <div className="mt-8 hidden md:flex items-center justify-center gap-1.5 text-xs text-white/70">
            <Github size={14} />
            <span>Open source on</span>
            <a
              href="https://github.com/frobinson47/cookslate"
              target="_blank"
              rel="noopener noreferrer"
              className="text-white hover:underline underline-offset-2 font-semibold"
            >
              GitHub
            </a>
          </div>
        </div>
      </div>

      {/* ── Form panel — clean, focused ── */}
      <div className="relative flex-1 flex items-center justify-center p-6 md:p-12 bg-cream">
        <div className="w-full max-w-sm motion-safe:animate-[login-fade-up_0.7s_var(--ease-emphasize)_0.12s_both]">
          <h2 className="text-2xl font-bold text-terracotta mb-1 font-serif">Sign In</h2>
          <p className="text-sage-dark text-sm mb-6">Welcome back — let's get cooking.</p>

          {error && (
            <div className="flex items-center gap-2 p-3 mb-4 rounded-xl bg-red-50 text-red-600 text-sm">
              <AlertCircle size={16} className="shrink-0" />
              <span>{error}</span>
            </div>
          )}

          {ssoEnabled && (
            <>
              <Button
                variant="outline"
                className="w-full transition-transform duration-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] hover:shadow-warm"
                size="lg"
                disabled={isSubmitting}
                onClick={() => { window.location.href = '/api/auth/oauth/redirect'; }}
              >
                <Shield size={18} />
                Log in with Authentik
              </Button>
              <div className="flex items-center gap-3 my-4">
                <div className="flex-1 h-px bg-cream-dark" />
                <span className="text-xs text-warm-gray">or</span>
                <div className="flex-1 h-px bg-cream-dark" />
              </div>
            </>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="rounded-xl transition-shadow duration-300 focus-within:shadow-[0_0_0_4px_rgba(193,105,79,0.12)]">
              <Input
                label="Username"
                labelClassName="text-sage-dark"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                placeholder="Enter your username"
                required
                autoComplete="username"
                autoFocus={!ssoEnabled}
              />
            </div>

            <div className="rounded-xl transition-shadow duration-300 focus-within:shadow-[0_0_0_4px_rgba(193,105,79,0.12)]">
              <Input
                label="Password"
                labelClassName="text-sage-dark"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Enter your password"
                required
                autoComplete="current-password"
              />
            </div>

            <Button
              type="submit"
              disabled={isSubmitting}
              className="w-full transition-transform duration-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] hover:shadow-warm-lg"
              size="lg"
            >
              {isSubmitting ? 'Signing in...' : 'Sign In'}
            </Button>

            <div className="text-center">
              <Link
                to="/forgot-password"
                className="text-sm text-terracotta hover:underline underline-offset-2"
              >
                Forgot password?
              </Link>
            </div>
          </form>

          <div className="mt-4 pt-4 border-t border-cream-dark">
            <Button
              variant="outline"
              disabled={isSubmitting}
              className="w-full transition-transform duration-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] hover:shadow-warm"
              size="lg"
              onClick={handleDemo}
            >
              <Eye size={18} />
              Try Demo
            </Button>
            <p className="text-xs text-warm-gray text-center mt-2">
              Browse recipes in read-only mode
            </p>
          </div>

          <div className="flex md:hidden items-center justify-center gap-1.5 mt-6 text-xs text-warm-gray">
            <Github size={14} />
            <span>Open source on</span>
            <a
              href="https://github.com/frobinson47/cookslate"
              target="_blank"
              rel="noopener noreferrer"
              className="text-terracotta hover:underline underline-offset-2"
            >
              GitHub
            </a>
          </div>

          <p className="text-center text-xs text-sage-dark/70 mt-6">
            Cookslate is a product of <a href="https://www.fmrdigital.dev" className="hover:text-sage-dark">FMR Digital LLC</a> &middot; &copy; 2026 FMR Digital LLC &middot; All rights reserved.
          </p>
          <p className="text-center text-xs text-sage-dark/70 mt-2">
            <a href="https://cookslate.app/privacy" className="hover:text-sage-dark">Privacy Policy</a>
            {' '}&middot;{' '}
            <a href="https://cookslate.app/terms" className="hover:text-sage-dark">Terms of Service</a>
          </p>
        </div>
      </div>
    </div>
  );
}
