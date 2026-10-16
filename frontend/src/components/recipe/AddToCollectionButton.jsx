import React, { useState } from 'react';
import { FolderPlus } from 'lucide-react';
import Modal from '../ui/Modal';
import Button from '../ui/Button';
import * as api from '../../services/api';
import { useToast } from '../../hooks/useToast';

export default function AddToCollectionButton({ recipeId }) {
  const toast = useToast();
  const [open, setOpen] = useState(false);
  const [collections, setCollections] = useState([]);
  const [selectedId, setSelectedId] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleOpen = () => {
    setOpen(true);
    api.getCollections()
      .then((data) => setCollections(data.collections || data || []))
      .catch(() => setCollections([]));
  };

  const handleAdd = async () => {
    if (!selectedId) return;
    setIsLoading(true);
    try {
      await api.addRecipeToCollection(selectedId, recipeId);
      const name = collections.find((c) => String(c.id) === String(selectedId))?.name;
      toast.success(name ? `Added to ${name}` : 'Added to collection');
      setOpen(false);
      setSelectedId('');
    } catch (err) {
      toast.error(err.message || 'Failed to add to collection');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <>
      <Button variant="secondary" onClick={handleOpen} className="print:hidden">
        <FolderPlus size={18} />
        Add to Collection
      </Button>
      <Modal isOpen={open} onClose={() => setOpen(false)} title="Add to Collection" size="sm">
        {collections.length === 0 ? (
          <p className="text-warm-gray text-sm">No collections yet. Create one from the Collections page first.</p>
        ) : (
          <div className="space-y-4">
            <select
              value={selectedId}
              onChange={(e) => setSelectedId(e.target.value)}
              className="w-full px-4 py-2.5 rounded-xl border border-cream-dark bg-surface text-brown focus:outline-none focus:border-terracotta"
            >
              <option value="">Choose a collection...</option>
              {collections.map((c) => (
                <option key={c.id} value={c.id}>{c.name}</option>
              ))}
            </select>
            <div className="flex gap-3 justify-end">
              <Button variant="ghost" onClick={() => setOpen(false)}>Cancel</Button>
              <Button onClick={handleAdd} disabled={isLoading || !selectedId}>Add</Button>
            </div>
          </div>
        )}
      </Modal>
    </>
  );
}
