import React, { useState, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { Camera, AlertCircle } from 'lucide-react';
import Button from '../ui/Button';
import Spinner from '../ui/Spinner';

export default function ImportImageForm({ onImportSuccess, isLoading, onImport, keyConfigured }) {
  const navigate = useNavigate();
  const [error, setError] = useState(null);
  const [preview, setPreview] = useState(null);
  const fileRef = useRef(null);

  const handleFileChange = async (e) => {
    const file = e.target.files?.[0];
    setError(null);
    if (!file) return;

    setPreview(URL.createObjectURL(file));

    try {
      const data = await onImport(file);
      if (onImportSuccess) {
        onImportSuccess(data);
      }
    } catch (err) {
      setError(err.message || 'Failed to import recipe from image');
    }
  };

  if (!keyConfigured) {
    return (
      <div className="bg-surface rounded-2xl shadow-md p-6 text-center">
        <Camera size={20} className="text-terracotta mx-auto mb-2" />
        <p className="text-sm text-brown-light mb-3">
          Connect your OpenAI API key in Settings to import recipes from photos.
        </p>
        <Button onClick={() => navigate('/settings')}>Go to Settings</Button>
      </div>
    );
  }

  return (
    <div className="bg-surface rounded-2xl shadow-md p-6">
      <h3 className="text-lg font-bold text-brown mb-4 flex items-center gap-2">
        <Camera size={20} className="text-terracotta" />
        Import from Photo
      </h3>

      <input
        ref={fileRef}
        type="file"
        accept="image/jpeg,image/png,image/webp,image/gif"
        onChange={handleFileChange}
        disabled={isLoading}
        className="block w-full text-sm text-brown-light file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-terracotta file:text-white file:text-sm file:font-medium hover:file:bg-terracotta/90 disabled:opacity-50"
      />

      {preview && (
        <img src={preview} alt="" className="mt-3 rounded-lg max-h-48 mx-auto" />
      )}

      {error && (
        <div className="flex items-center gap-2 text-red-500 text-sm mt-3">
          <AlertCircle size={16} />
          <span>{error}</span>
        </div>
      )}

      {isLoading && (
        <div className="flex items-center gap-2 mt-3 text-sm text-warm-gray">
          <Spinner size="sm" />
          Reading recipe from photo...
        </div>
      )}

      <p className="mt-3 text-xs text-warm-gray text-center">
        Works best with clear, well-lit photos of a single recipe.
      </p>
    </div>
  );
}
