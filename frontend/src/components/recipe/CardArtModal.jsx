import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Sparkles, AlertCircle, ArrowLeft, Printer } from 'lucide-react';
import Button from '../ui/Button';
import Spinner from '../ui/Spinner';
import Modal from '../ui/Modal';
import { generateCardArt } from '../../services/api';
import { fullImageUrl } from '../../utils/imageUrl';
import { CARD_ART_TEMPLATES } from '../../constants/cardArtTemplates';

const ERROR_MESSAGES = {
  no_api_key: 'Add your OpenAI API key in Settings to use this feature.',
  invalid_api_key: 'Your OpenAI API key was rejected. Check it in Settings.',
  rate_limited: 'OpenAI rate-limited this request. Wait a moment and try again.',
  request_failed: "Couldn't reach OpenAI. Try again in a moment.",
};

export default function CardArtModal({ recipeId, isOpen, onClose, keyConfigured, onGenerated, existingTemplates = [] }) {
  const navigate = useNavigate();
  const [selectedTemplate, setSelectedTemplate] = useState(null);
  const [pendingTemplate, setPendingTemplate] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [imagePath, setImagePath] = useState(null);

  const reset = () => {
    setSelectedTemplate(null);
    setPendingTemplate(null);
    setLoading(false);
    setError(null);
    setImagePath(null);
  };

  const handleClose = () => {
    reset();
    onClose();
  };

  const runGenerate = async (templateKey) => {
    setSelectedTemplate(templateKey);
    setPendingTemplate(null);
    setError(null);
    setImagePath(null);
    setLoading(true);
    try {
      const result = await generateCardArt(recipeId, templateKey);
      if (result.error_code) {
        setError(ERROR_MESSAGES[result.error_code] || result.error || 'Something went wrong generating this card.');
      } else {
        setImagePath(result.image_path);
        if (onGenerated) {
          onGenerated({ template: templateKey, image_path: result.image_path });
        }
      }
    } catch (err) {
      setError(err.message || 'Something went wrong generating this card.');
    } finally {
      setLoading(false);
    }
  };

  const handleSelectTemplate = (templateKey) => {
    // Already generated for this recipe — just view it, no new API cost.
    if (existingTemplates.includes(templateKey)) {
      runGenerate(templateKey);
    } else {
      setPendingTemplate(templateKey);
    }
  };

  const handleBackToGallery = () => {
    reset();
  };

  if (!keyConfigured) {
    return (
      <Modal isOpen={isOpen} onClose={handleClose} title="Generate Recipe Art">
        <div className="text-center py-4">
          <Sparkles size={20} className="text-terracotta mx-auto mb-2" />
          <p className="text-sm text-brown-light mb-3">
            Connect your OpenAI API key in Settings to generate styled recipe card art.
          </p>
          <Button onClick={() => { handleClose(); navigate('/settings'); }}>Go to Settings</Button>
        </div>
      </Modal>
    );
  }

  return (
    <Modal isOpen={isOpen} onClose={handleClose} title="Generate Recipe Art" size="lg">
      {pendingTemplate ? (
        <div>
          <button
            onClick={() => setPendingTemplate(null)}
            className="flex items-center gap-1 text-sm text-brown-light hover:text-brown mb-4"
          >
            <ArrowLeft size={16} />
            Choose a different style
          </button>
          <p className="text-sm font-semibold text-brown mb-1">
            {CARD_ART_TEMPLATES.find((t) => t.key === pendingTemplate)?.label}
          </p>
          <p className="text-xs text-warm-gray mb-4">
            {CARD_ART_TEMPLATES.find((t) => t.key === pendingTemplate)?.description}
          </p>
          <div className="bg-terracotta-50 border border-cream-dark/30 rounded-xl p-3 mb-4">
            <p className="text-xs text-brown-light">
              Generating a new style calls the OpenAI image API with your key — this is a real charge
              on your OpenAI account and takes 1–4 minutes. Once generated, this style is cached for this
              recipe and viewing it again won't cost anything further.
            </p>
          </div>
          <Button onClick={() => runGenerate(pendingTemplate)}>
            <Sparkles size={16} />
            Generate This Card
          </Button>
        </div>
      ) : !selectedTemplate ? (
        <>
          <p className="text-sm text-brown-light mb-4">
            Pick a style. This creates stylized recipe-card art for printing or sharing —
            not a photo of the dish as actually cooked.
          </p>
          <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
            {CARD_ART_TEMPLATES.map((tpl) => {
              const generated = existingTemplates.includes(tpl.key);
              return (
                <button
                  key={tpl.key}
                  onClick={() => handleSelectTemplate(tpl.key)}
                  className="text-left p-3 rounded-xl border border-cream-dark hover:border-terracotta hover:bg-cream-dark transition-colors duration-200 min-h-[44px] relative"
                >
                  {generated && (
                    <span className="absolute top-2 right-2 text-[10px] font-semibold text-white bg-sage px-1.5 py-0.5 rounded-full">
                      Generated
                    </span>
                  )}
                  <p className="text-sm font-semibold text-brown pr-12">{tpl.label}</p>
                  <p className="text-xs text-warm-gray mt-1">{tpl.description}</p>
                </button>
              );
            })}
          </div>
        </>
      ) : (
        <div>
          <button
            onClick={handleBackToGallery}
            className="flex items-center gap-1 text-sm text-brown-light hover:text-brown mb-4"
          >
            <ArrowLeft size={16} />
            Choose a different style
          </button>

          {loading && (
            <div className="flex flex-col items-center gap-3 py-10">
              <Spinner />
              <p className="text-sm text-warm-gray text-center">
                Generating your card art — this can take a couple of minutes.
              </p>
            </div>
          )}

          {error && (
            <div className="flex items-center gap-2 text-red-500 text-sm py-4">
              <AlertCircle size={16} />
              <span>{error}</span>
            </div>
          )}

          {imagePath && !loading && (
            <div className="space-y-4">
              <img
                src={fullImageUrl(imagePath)}
                alt="Generated recipe card art"
                className="w-full rounded-xl shadow-md"
              />
              <div className="flex justify-center gap-2">
                <Button
                  variant="outline"
                  onClick={() => window.open(`/recipe/${recipeId}/card-art/${selectedTemplate}/print`, '_blank', 'noopener')}
                >
                  <Printer size={18} />
                  Open to Print
                </Button>
              </div>
            </div>
          )}
        </div>
      )}
    </Modal>
  );
}
