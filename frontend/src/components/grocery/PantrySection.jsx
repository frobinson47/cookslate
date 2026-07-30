import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Plus, X, Warehouse, Receipt, CalendarClock, ScanLine } from 'lucide-react';
import usePantry from '../../hooks/usePantry';
import Button from '../ui/Button';

function isExpiringSoon(dateStr) {
  if (!dateStr) return false;
  const days = (new Date(dateStr) - new Date(new Date().toDateString())) / 86400000;
  return days <= 3;
}

export default function PantrySection() {
  const { items, fetchPantry, addItem, removeItem, setExpiration } = usePantry();
  const [newName, setNewName] = useState('');
  const [editingExpiry, setEditingExpiry] = useState(null);

  useEffect(() => {
    fetchPantry();
  }, [fetchPantry]);

  const handleAdd = async (e) => {
    e.preventDefault();
    if (!newName.trim()) return;
    try {
      await addItem(newName.trim());
      setNewName('');
    } catch {
      // Error in hook
    }
  };

  return (
    <div className="bg-surface rounded-2xl shadow-md overflow-hidden">
      <div className="px-4 py-3 border-b border-cream-dark flex items-center gap-2">
        <Warehouse size={18} className="text-terracotta" />
        <h2 className="font-semibold text-brown">My Pantry</h2>
        <span className="text-xs text-warm-gray ml-auto">{items.length} items</span>
        <Link
          to="/scan-receipt"
          className="flex items-center gap-1 text-xs text-terracotta hover:underline"
        >
          <Receipt size={14} />
          Scan Receipt
        </Link>
        <Link
          to="/scan-pantry"
          className="flex items-center gap-1 text-xs text-terracotta hover:underline"
        >
          <ScanLine size={14} />
          Scan Pantry
        </Link>
      </div>

      <p className="px-4 py-2 text-xs text-warm-gray">
        Items here are auto-marked on grocery lists so you know you already have them.
      </p>

      {items.length > 0 && (
        <div className="px-4 pb-2 flex flex-wrap gap-2">
          {items.map(item => (
            <span
              key={item.id}
              className={`inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm ${
                isExpiringSoon(item.expiration_date)
                  ? 'bg-terracotta/10 text-terracotta'
                  : 'bg-cream-dark/60 text-brown-light'
              }`}
            >
              {item.ingredient_name}
              {item.quantity != null && (
                <span className="text-warm-gray">
                  ({item.quantity}{item.unit ? ` ${item.unit}` : ''})
                </span>
              )}
              {editingExpiry === item.id ? (
                <input
                  type="date"
                  autoFocus
                  defaultValue={item.expiration_date || ''}
                  onBlur={(e) => { setExpiration(item.id, e.target.value || null); setEditingExpiry(null); }}
                  onKeyDown={(e) => { if (e.key === 'Enter') e.target.blur(); }}
                  className="text-xs bg-transparent border-b border-current focus:outline-none"
                />
              ) : (
                <button
                  onClick={() => setEditingExpiry(item.id)}
                  className="p-0.5 hover:text-terracotta transition-colors"
                  aria-label={item.expiration_date ? `Change expiration date for ${item.ingredient_name}` : `Set expiration date for ${item.ingredient_name}`}
                  title={item.expiration_date ? `Expires ${item.expiration_date}` : 'Set expiration date'}
                >
                  <CalendarClock size={13} />
                </button>
              )}
              <button
                onClick={() => removeItem(item.id)}
                className="p-0.5 hover:text-red-500 transition-colors"
                aria-label={`Remove ${item.ingredient_name} from pantry`}
              >
                <X size={14} />
              </button>
            </span>
          ))}
        </div>
      )}

      <form onSubmit={handleAdd} className="p-3 border-t border-cream-dark">
        <div className="flex gap-2">
          <input
            type="text"
            value={newName}
            onChange={(e) => setNewName(e.target.value)}
            placeholder="Add to pantry (e.g., salt, olive oil)..."
            className="flex-1 px-4 py-2.5 rounded-xl border border-cream-dark text-brown placeholder:text-warm-gray focus:outline-none focus:border-terracotta transition-colors duration-200 min-h-[44px]"
          />
          <Button type="submit" disabled={!newName.trim()} size="sm">
            <Plus size={16} />
          </Button>
        </div>
      </form>
    </div>
  );
}
