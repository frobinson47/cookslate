import React, { useState } from 'react';
import { Plus, Trash2 } from 'lucide-react';
import Input from '../ui/Input';
import Button from '../ui/Button';
import Spinner from '../ui/Spinner';

function emptyItem() {
  return { name: '', quantity: '', unit: '', expiration_date: '' };
}

export default function PantryScanReviewForm({ initialData, onConfirm, isLoading }) {
  const [items, setItems] = useState(
    (initialData?.items || []).map((i) => ({
      name: i.name || '',
      quantity: i.quantity ?? '',
      unit: i.unit || '',
      expiration_date: '',
    }))
  );
  const [error, setError] = useState(null);

  const updateItem = (index, field, value) => {
    setItems((prev) => prev.map((item, i) => (i === index ? { ...item, [field]: value } : item)));
  };

  const removeItem = (index) => {
    setItems((prev) => prev.filter((_, i) => i !== index));
  };

  const addItem = () => {
    setItems((prev) => [...prev, emptyItem()]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);

    const validItems = items
      .filter((item) => item.name.trim())
      .map((item) => ({
        name: item.name.trim(),
        quantity: item.quantity === '' ? null : Number(item.quantity),
        unit: item.unit.trim() || null,
        expiration_date: item.expiration_date || null,
      }));

    if (validItems.length === 0) {
      setError('Add at least one item before saving.');
      return;
    }

    await onConfirm(validItems);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div className="bg-surface rounded-2xl shadow-md p-6 space-y-3">
        <h3 className="font-semibold text-brown">Items</h3>

        {items.map((item, index) => (
          <div key={index} className="flex flex-wrap items-end gap-2">
            <div className="flex-1 min-w-[160px]">
              <Input
                label={index === 0 ? 'Item' : undefined}
                value={item.name}
                onChange={(e) => updateItem(index, 'name', e.target.value)}
                placeholder="Item name"
              />
            </div>
            <div className="w-24">
              <Input
                label={index === 0 ? 'Qty' : undefined}
                type="number"
                step="0.01"
                value={item.quantity}
                onChange={(e) => updateItem(index, 'quantity', e.target.value)}
              />
            </div>
            <div className="w-24">
              <Input
                label={index === 0 ? 'Unit' : undefined}
                value={item.unit}
                onChange={(e) => updateItem(index, 'unit', e.target.value)}
                placeholder="lb, ct..."
              />
            </div>
            <div className="w-36">
              <Input
                label={index === 0 ? 'Expires (optional)' : undefined}
                type="date"
                value={item.expiration_date}
                onChange={(e) => updateItem(index, 'expiration_date', e.target.value)}
              />
            </div>
            <Button
              type="button"
              variant="ghost"
              size="sm"
              onClick={() => removeItem(index)}
              aria-label={`Remove ${item.name || 'item'}`}
            >
              <Trash2 size={16} />
            </Button>
          </div>
        ))}

        <Button type="button" variant="outline" size="sm" onClick={addItem}>
          <Plus size={16} />
          Add item
        </Button>
      </div>

      {error && <p className="text-sm text-red-500">{error}</p>}

      <div className="flex items-center gap-3">
        <Button type="submit" disabled={isLoading}>
          {isLoading ? <Spinner size="sm" /> : 'Add to Pantry'}
        </Button>
      </div>
    </form>
  );
}
