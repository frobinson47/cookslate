import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import ReceiptUploadForm from '../components/receipt/ReceiptUploadForm';
import ReceiptReviewForm from '../components/receipt/ReceiptReviewForm';
import useShoppingTrips from '../hooks/useShoppingTrips';
import useDocumentTitle from '../hooks/useDocumentTitle';
import { getOpenAiKeyStatus } from '../services/api';

export default function ScanReceiptPage() {
  useDocumentTitle('Scan Receipt');

  const navigate = useNavigate();
  const { isLoading, importReceipt, createTrip } = useShoppingTrips();
  const [parsedData, setParsedData] = useState(null);
  const [openAiKeyConfigured, setOpenAiKeyConfigured] = useState(false);

  useEffect(() => {
    getOpenAiKeyStatus()
      .then((result) => setOpenAiKeyConfigured(!!result.configured))
      .catch(() => {});
  }, []);

  const handleImportSuccess = (data) => {
    if (data.error && (!data.items || data.items.length === 0)) {
      throw new Error(data.error);
    }
    setParsedData(data);
  };

  const handleConfirm = async (tripData) => {
    try {
      const result = await createTrip(tripData);
      const newId = result?.id;
      navigate(newId ? `/spending-history/${newId}` : '/spending-history');
    } catch {
      // Error shown in form
    }
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-brown">
          {parsedData ? 'Review Receipt' : 'Scan Receipt'}
        </h1>
        <button
          onClick={() => navigate('/grocery')}
          className="text-sm text-warm-gray hover:text-brown transition-colors"
        >
          Back
        </button>
      </div>

      {!parsedData ? (
        <ReceiptUploadForm
          onImportSuccess={handleImportSuccess}
          onImport={importReceipt}
          isLoading={isLoading}
          keyConfigured={openAiKeyConfigured}
        />
      ) : (
        <>
          <div className="p-3 rounded-xl bg-sage-light/20 text-sage-dark text-sm">
            Receipt read! Review the items below, then save — matched items will be added to your pantry.
          </div>
          <ReceiptReviewForm initialData={parsedData} onConfirm={handleConfirm} isLoading={isLoading} />
        </>
      )}
    </div>
  );
}
