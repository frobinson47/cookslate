import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import PantryScanUploadForm from '../components/pantry/PantryScanUploadForm';
import PantryScanReviewForm from '../components/pantry/PantryScanReviewForm';
import usePantry from '../hooks/usePantry';
import useDocumentTitle from '../hooks/useDocumentTitle';
import { getOpenAiKeyStatus } from '../services/api';

export default function ScanPantryPage() {
  useDocumentTitle('Scan Pantry');

  const navigate = useNavigate();
  const { isLoading, scanPantry, bulkAddItems } = usePantry();
  const [parsedData, setParsedData] = useState(null);
  const [openAiKeyConfigured, setOpenAiKeyConfigured] = useState(false);

  useEffect(() => {
    getOpenAiKeyStatus()
      .then((result) => setOpenAiKeyConfigured(!!result.configured))
      .catch(() => {});
  }, []);

  const handleScanSuccess = (data) => {
    if (data.error && (!data.items || data.items.length === 0)) {
      throw new Error(data.error);
    }
    setParsedData(data);
  };

  const handleConfirm = async (items) => {
    try {
      await bulkAddItems(items);
      navigate('/grocery');
    } catch {
      // Error shown in form
    }
  };

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-brown">
          {parsedData ? 'Review Items' : 'Scan Pantry'}
        </h1>
        <button
          onClick={() => navigate('/grocery')}
          className="text-sm text-warm-gray hover:text-brown transition-colors"
        >
          Back
        </button>
      </div>

      {!parsedData ? (
        <PantryScanUploadForm
          onScanSuccess={handleScanSuccess}
          onScan={scanPantry}
          isLoading={isLoading}
          keyConfigured={openAiKeyConfigured}
        />
      ) : (
        <>
          <div className="p-3 rounded-xl bg-sage-light/20 text-sage-dark text-sm">
            Photo read! Review the items below, then add them to your pantry.
          </div>
          <PantryScanReviewForm initialData={parsedData} onConfirm={handleConfirm} isLoading={isLoading} />
        </>
      )}
    </div>
  );
}
