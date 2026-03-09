import React from 'react';
import { AlertTriangle } from 'lucide-react';

export default class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError() {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    console.error('ErrorBoundary caught:', error, errorInfo);
  }

  render() {
    if (this.state.hasError) {
      return (
        <div className="flex flex-col items-center justify-center py-16 px-4 text-center">
          <AlertTriangle size={48} className="text-terracotta mb-4" />
          <h2 className="text-xl font-bold text-brown font-serif mb-2">Something went wrong</h2>
          <p className="text-brown-light mb-6 max-w-md">
            An unexpected error occurred. Try refreshing the page.
          </p>
          <button
            onClick={() => {
              this.setState({ hasError: false });
              window.location.reload();
            }}
            className="px-6 py-3 bg-terracotta text-white rounded-xl font-semibold hover:bg-terracotta-dark transition-colors duration-200 min-h-[44px]"
          >
            Refresh Page
          </button>
        </div>
      );
    }

    return this.props.children;
  }
}
