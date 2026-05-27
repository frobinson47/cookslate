import React from 'react';
import { Link } from 'react-router-dom';
import { Home, ChefHat } from 'lucide-react';
import CookslateLogo from '../components/ui/CookslateLogo';
import Button from '../components/ui/Button';

export default function NotFoundPage() {
  return (
    <div className="min-h-screen flex items-center justify-center bg-[var(--color-bg)] px-4">
      <div className="max-w-md w-full text-center">
        <div className="flex justify-center mb-6">
          <CookslateLogo className="w-16 h-16" />
        </div>
        <h1 className="text-6xl font-bold text-[var(--color-text)] mb-2">404</h1>
        <p className="text-xl text-[var(--color-text)] mb-2">Page not found</p>
        <p className="text-[var(--color-text-muted)] mb-8">
          The recipe you're looking for seems to have walked off the cutting board.
        </p>
        <div className="flex flex-col sm:flex-row gap-3 justify-center">
          <Link to="/">
            <Button variant="primary" className="w-full sm:w-auto">
              <Home className="w-4 h-4 mr-2" />
              Back to home
            </Button>
          </Link>
          <Link to="/discover">
            <Button variant="secondary" className="w-full sm:w-auto">
              <ChefHat className="w-4 h-4 mr-2" />
              Browse recipes
            </Button>
          </Link>
        </div>
      </div>
    </div>
  );
}
