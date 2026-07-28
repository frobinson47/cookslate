import React, { useState, useEffect } from 'react';
import { X, Tag, FolderPlus, Trash2 } from 'lucide-react';
import Modal from '../ui/Modal';
import Button from '../ui/Button';
import Input from '../ui/Input';
import * as api from '../../services/api';
import { useToast } from '../../hooks/useToast';

export default function BulkActionToolbar({ selectedIds, onClear, onComplete }) {
  const toast = useToast();
  const [tagModalOpen, setTagModalOpen] = useState(false);
  const [collectionModalOpen, setCollectionModalOpen] = useState(false);
  const [deleteConfirmOpen, setDeleteConfirmOpen] = useState(false);
  const [tagInput, setTagInput] = useState('');
  const [collections, setCollections] = useState([]);
  const [selectedCollectionId, setSelectedCollectionId] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const count = selectedIds.size;
  const ids = Array.from(selectedIds);

  useEffect(() => {
    if (collectionModalOpen) {
      api.getCollections()
        .then((data) => setCollections(data.collections || data || []))
        .catch(() => setCollections([]));
    }
  }, [collectionModalOpen]);

  const handleTag = async (e) => {
    e.preventDefault();
    const tags = tagInput.split(',').map((t) => t.trim()).filter(Boolean);
    if (tags.length === 0) return;

    setIsLoading(true);
    try {
      await api.bulkTagRecipes(ids, tags);
      toast.success(`Tagged ${count} recipe${count === 1 ? '' : 's'}`);
      setTagModalOpen(false);
      setTagInput('');
      onComplete();
    } catch (err) {
      toast.error(err.message || 'Failed to tag recipes');
    } finally {
      setIsLoading(false);
    }
  };

  const handleAddToCollection = async () => {
    if (!selectedCollectionId) return;

    setIsLoading(true);
    try {
      await api.addRecipesToCollectionBulk(selectedCollectionId, ids);
      toast.success(`Added ${count} recipe${count === 1 ? '' : 's'} to collection`);
      setCollectionModalOpen(false);
      setSelectedCollectionId('');
      onComplete();
    } catch (err) {
      toast.error(err.message || 'Failed to add to collection');
    } finally {
      setIsLoading(false);
    }
  };

  const handleDelete = async () => {
    setIsLoading(true);
    try {
      const result = await api.bulkDeleteRecipes(ids);
      const deletedCount = result.deleted?.length ?? count;
      toast.success(`Deleted ${deletedCount} recipe${deletedCount === 1 ? '' : 's'}`);
      if (result.skipped?.length) {
        toast.warning(`${result.skipped.length} recipe${result.skipped.length === 1 ? '' : 's'} skipped (not yours to delete)`);
      }
      setDeleteConfirmOpen(false);
      onComplete();
    } catch (err) {
      toast.error(err.message || 'Failed to delete recipes');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <>
      <div className="fixed bottom-20 md:bottom-6 left-1/2 -translate-x-1/2 z-30 bg-brown text-white rounded-2xl shadow-lg px-4 py-3 flex items-center gap-4">
        <span className="font-semibold text-sm whitespace-nowrap">{count} selected</span>
        <button
          onClick={() => setTagModalOpen(true)}
          className="flex items-center gap-1 text-sm hover:text-terracotta-light transition-colors"
        >
          <Tag size={16} /> Tag
        </button>
        <button
          onClick={() => setCollectionModalOpen(true)}
          className="flex items-center gap-1 text-sm hover:text-terracotta-light transition-colors"
        >
          <FolderPlus size={16} /> Collection
        </button>
        <button
          onClick={() => setDeleteConfirmOpen(true)}
          className="flex items-center gap-1 text-sm hover:text-red-300 transition-colors"
        >
          <Trash2 size={16} /> Delete
        </button>
        <button
          onClick={onClear}
          className="p-1 hover:bg-white/10 rounded-full transition-colors"
          aria-label="Clear selection"
        >
          <X size={16} />
        </button>
      </div>

      <Modal isOpen={tagModalOpen} onClose={() => setTagModalOpen(false)} title={`Tag ${count} recipe${count === 1 ? '' : 's'}`} size="sm">
        <form onSubmit={handleTag} className="space-y-4">
          <Input
            label="Tags (comma-separated)"
            value={tagInput}
            onChange={(e) => setTagInput(e.target.value)}
            placeholder="quick, weeknight"
            autoFocus
          />
          <div className="flex gap-3 justify-end">
            <Button type="button" variant="ghost" onClick={() => setTagModalOpen(false)}>Cancel</Button>
            <Button type="submit" disabled={isLoading || !tagInput.trim()}>Add Tags</Button>
          </div>
        </form>
      </Modal>

      <Modal isOpen={collectionModalOpen} onClose={() => setCollectionModalOpen(false)} title={`Add ${count} recipe${count === 1 ? '' : 's'} to Collection`} size="sm">
        {collections.length === 0 ? (
          <p className="text-warm-gray text-sm">No collections yet. Create one from the Collections page first.</p>
        ) : (
          <div className="space-y-4">
            <select
              value={selectedCollectionId}
              onChange={(e) => setSelectedCollectionId(e.target.value)}
              className="w-full px-4 py-2.5 rounded-xl border border-cream-dark bg-surface text-brown focus:outline-none focus:border-terracotta"
            >
              <option value="">Choose a collection...</option>
              {collections.map((c) => (
                <option key={c.id} value={c.id}>{c.name}</option>
              ))}
            </select>
            <div className="flex gap-3 justify-end">
              <Button variant="ghost" onClick={() => setCollectionModalOpen(false)}>Cancel</Button>
              <Button onClick={handleAddToCollection} disabled={isLoading || !selectedCollectionId}>Add</Button>
            </div>
          </div>
        )}
      </Modal>

      <Modal isOpen={deleteConfirmOpen} onClose={() => setDeleteConfirmOpen(false)} title="Delete Recipes" size="sm">
        <p className="text-brown-light mb-6">
          Delete {count} recipe{count === 1 ? '' : 's'}? This can&rsquo;t be undone.
        </p>
        <div className="flex gap-3 justify-end">
          <Button variant="ghost" onClick={() => setDeleteConfirmOpen(false)}>Cancel</Button>
          <Button variant="danger" onClick={handleDelete} disabled={isLoading}>Delete</Button>
        </div>
      </Modal>
    </>
  );
}
