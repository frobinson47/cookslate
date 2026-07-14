import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { Printer } from 'lucide-react';
import { getCardArt } from '../services/api';
import { fullImageUrl } from '../utils/imageUrl';
import { cardArtTemplateLabel } from '../constants/cardArtTemplates';
import Button from '../components/ui/Button';
import Spinner from '../components/ui/Spinner';
import useDocumentTitle from '../hooks/useDocumentTitle';

export default function CardArtPrintPage() {
  const { id, template } = useParams();
  const [imagePath, setImagePath] = useState(null);
  const [error, setError] = useState(null);

  useDocumentTitle(`${cardArtTemplateLabel(template)} — Print`);

  useEffect(() => {
    getCardArt(id, template)
      .then((result) => setImagePath(result.image_path))
      .catch(() => setError('This card art has not been generated yet.'));
  }, [id, template]);

  return (
    <div className="min-h-screen bg-cream flex flex-col items-center py-8 px-4">
      <div className="print:hidden flex items-center gap-3 mb-6">
        <Button onClick={() => window.print()} disabled={!imagePath}>
          <Printer size={18} />
          Print
        </Button>
      </div>

      {error && <p className="text-red-500">{error}</p>}

      {!imagePath && !error && (
        <div className="py-16">
          <Spinner />
        </div>
      )}

      {imagePath && (
        <img
          src={fullImageUrl(imagePath)}
          alt={`${cardArtTemplateLabel(template)} recipe card art`}
          className="max-w-full rounded-xl shadow-md print:shadow-none print:rounded-none print:max-w-none"
        />
      )}

      <style>{`
        @media print {
          @page { size: auto; margin: 0; }
          body { background: white !important; margin: 0 !important; }
          img { width: 100%; height: auto; display: block; }
        }
      `}</style>
    </div>
  );
}
