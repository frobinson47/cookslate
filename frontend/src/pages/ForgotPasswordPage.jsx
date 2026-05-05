import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { Mail, AlertCircle, CheckCircle } from 'lucide-react';
import CookslateLogo from '../components/ui/CookslateLogo';
import Button from '../components/ui/Button';
import Input from '../components/ui/Input';
import * as api from '../services/api';
import useDocumentTitle from '../hooks/useDocumentTitle';

export default function ForgotPasswordPage() {
  useDocumentTitle('Forgot Password');

  const [email, setEmail] = useState('');
  const [error, setError] = useState(null);
  const [submitted, setSubmitted] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setIsSubmitting(true);
    try {
      await api.forgotPassword(email);
      setSubmitted(true);
    } catch (err) {
      setError(err.message || 'Something went wrong. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-cream flex items-center justify-center p-4">
      <div className="w-full max-w-sm">
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center mb-4">
            <CookslateLogo size={64} className="text-terracotta" />
          </div>
          <h1 className="text-3xl font-bold text-brown font-display">Cookslate</h1>
        </div>

        <div className="bg-surface rounded-2xl shadow-md p-6">
          <h2 className="text-xl font-bold text-brown mb-2 text-center">Forgot your password?</h2>
          <p className="text-sm text-warm-gray text-center mb-6">
            Enter your email and we'll send you a link to set a new one.
          </p>

          {submitted ? (
            <div className="text-center">
              <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-sage-light/30 text-sage mb-3">
                <CheckCircle size={24} />
              </div>
              <p className="text-brown font-medium mb-2">Check your inbox</p>
              <p className="text-sm text-warm-gray mb-6">
                If that email is registered, a reset link is on its way. The link expires in 24 hours.
              </p>
              <Link to="/login" className="text-sm text-terracotta hover:underline">
                Back to sign in
              </Link>
            </div>
          ) : (
            <>
              {error && (
                <div className="flex items-center gap-2 p-3 mb-4 rounded-xl bg-red-50 text-red-600 text-sm">
                  <AlertCircle size={16} className="shrink-0" />
                  <span>{error}</span>
                </div>
              )}

              <form onSubmit={handleSubmit} className="space-y-4">
                <Input
                  label="Email"
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="you@example.com"
                  required
                  autoFocus
                  autoComplete="email"
                />
                <Button
                  type="submit"
                  disabled={isSubmitting}
                  className="w-full"
                  size="lg"
                >
                  <Mail size={18} />
                  {isSubmitting ? 'Sending...' : 'Send reset link'}
                </Button>
              </form>

              <div className="mt-4 text-center">
                <Link to="/login" className="text-sm text-terracotta hover:underline">
                  Back to sign in
                </Link>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  );
}
