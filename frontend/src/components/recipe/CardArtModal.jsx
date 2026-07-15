import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Sparkles, AlertCircle, ArrowLeft, Printer, Copy, Check } from 'lucide-react';
import Button from '../ui/Button';
import Spinner from '../ui/Spinner';
import Modal from '../ui/Modal';
import { getCardArt, generateCardArt } from '../../services/api';
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
  const [loading, setLoading] = useState(false);
  const [generating, setGenerating] = useState(false);
  const [error, setError] = useState(null);
  const [imagePath, setImagePath] = useState(null);
  const [prompt, setPrompt] = useState(null);
  const [copied, setCopied] = useState(false);

  const reset = () => {
    setSelectedTemplate(null);
    setLoading(false);
    setGenerating(false);
    setError(null);
    setImagePath(null);
    setPrompt(null);
    setCopied(false);
  };

  const handleClose = () => {
    reset();
    onClose();
  };

  const handleSelectTemplate = async (templateKey) => {
    setSelectedTemplate(templateKey);
    setError(null);
    setImagePath(null);
    setPrompt(null);
    setCopied(false);
    setLoading(true);
    try {
      const result = await getCardArt(recipeId, templateKey);
      if (result.image_path) {
        setImagePath(result.image_path);
      } else {
        setPrompt(result.prompt);
      }
    } catch (err) {
      setError(err.message || 'Something went wrong loading this template.');
    } finally {
      setLoading(false);
    }
  };

  const handleGenerate = async () => {
    setGenerating(true);
    setError(null);
    try {
      const result = await generateCardArt(recipeId, selectedTemplate);
      if (result.error_code) {
        setError(ERROR_MESSAGES[result.error_code] || result.error || 'Something went wrong generating this card.');
      } else {
        setImagePath(result.image_path);
        setPrompt(null);
        if (onGenerated) {
          onGenerated({ template: selectedTemplate, image_path: result.image_path });
        }
      }
    } catch (err) {
      setError(err.message || 'Something went wrong generating this card.');
    } finally {
      setGenerating(false);
    }
  };

  const handleCopyPrompt = async () => {
    try {
      await navigator.clipboard.writeText(prompt);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    } catch {
      setError("Couldn't copy to clipboard. Select and copy the text manually.");
    }
  };

  const handleBackToGallery = () => {
    reset();
  };

  const selectedMeta = CARD_ART_TEMPLATES.find((t) => t.key === selectedTemplate);

  return (
    <Modal isOpen={isOpen} onClose={handleClose} title="Generate Recipe Art" size="lg">
      {!selectedTemplate ? (
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
            </div>
          )}

          {error && (
            <div className="flex items-center gap-2 text-red-500 text-sm py-4">
              <AlertCircle size={16} />
              <span>{error}</span>
            </div>
          )}

          {/* Not yet generated — show the built prompt for copy, or a Generate button if a key is configured */}
          {prompt && !loading && (
            <div className="space-y-4">
              <div>
                <p className="text-sm font-semibold text-brown">{selectedMeta?.label}</p>
                <p className="text-xs text-warm-gray">{selectedMeta?.description}</p>
              </div>

              <div>
                <p className="text-xs font-semibold text-brown-light mb-1">Prompt</p>
                <textarea
                  readOnly
                  value={prompt}
                  rows={8}
                  className="w-full text-xs text-brown-light bg-cream-dark/30 rounded-xl p-3 resize-none font-mono"
                  onFocus={(e) => e.target.select()}
                />
                <p className="text-xs text-warm-gray mt-1">
                  Paste this into ChatGPT (Plus/Pro with image generation) or another compatible image tool,
                  then use "Upload Custom Card" on the recipe page to add the result here.
                </p>
              </div>

              <Button variant="outline" onClick={handleCopyPrompt}>
                {copied ? <Check size={16} /> : <Copy size={16} />}
                {copied ? 'Copied!' : 'Copy Prompt'}
              </Button>

              <div className="border-t border-cream-dark pt-4">
                {keyConfigured ? (
                  <>
                    <div className="bg-terracotta-50 border border-cream-dark/30 rounded-xl p-3 mb-3">
                      <p className="text-xs text-brown-light">
                        Or generate it in-app using your OpenAI key — this is a real charge on your OpenAI
                        account and takes 1–4 minutes. Once generated, it's cached for this recipe and
                        viewing it again won't cost anything further.
                      </p>
                    </div>
                    <Button onClick={handleGenerate} disabled={generating}>
                      {generating ? <Spinner size="sm" /> : <Sparkles size={16} />}
                      {generating ? 'Generating…' : 'Generate This Card'}
                    </Button>
                  </>
                ) : (
                  <p className="text-xs text-warm-gray">
                    Or{' '}
                    <button onClick={() => { handleClose(); navigate('/settings'); }} className="text-terracotta underline">
                      add your OpenAI API key in Settings
                    </button>{' '}
                    to generate this in-app instead.
                  </p>
                )}
              </div>
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
