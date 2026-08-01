import React, { useState, useEffect, useCallback } from 'react';
import { useNavigate } from 'react-router-dom';
import { ChevronRight, ChevronLeft, ShoppingCart, Plus, RefreshCw, Trash2 } from 'lucide-react';
import Button from '../../../components/ui/Button';
import Spinner from '../../../components/ui/Spinner';
import GroceryItem from '../../../components/grocery/GroceryItem';
import * as api from '../../../services/api';

function getMonday(date) {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day + (day === 0 ? -6 : 1);
  d.setDate(diff);
  return d.toISOString().split('T')[0];
}

function addDays(weekStartStr, days) {
  const d = new Date(weekStartStr + 'T00:00:00');
  d.setDate(d.getDate() + days);
  return d.toISOString().split('T')[0];
}

function defaultListName(weekStart) {
  const monday = new Date(weekStart + 'T00:00:00');
  const sunday = new Date(monday);
  sunday.setDate(sunday.getDate() + 6);
  const opts = { month: 'short', day: 'numeric' };
  return `Meal Plan — ${monday.toLocaleDateString('en-US', opts)}-${sunday.toLocaleDateString('en-US', opts)}`;
}

export default function GroceryPanel({ canGenerate = true }) {
  const navigate = useNavigate();
  const [weekOffset, setWeekOffset] = useState(0); // 0 = this week, 1 = next week
  const [list, setList] = useState(null);
  const [loading, setLoading] = useState(true);
  const [actionLoading, setActionLoading] = useState(false);
  const [collapsed, setCollapsed] = useState(false);
  const [newItemName, setNewItemName] = useState('');

  const panelWeekStart = addDays(getMonday(new Date()), weekOffset * 7);

  const fetchList = useCallback(async () => {
    setLoading(true);
    try {
      const data = await api.getMealPlanGroceryForWeek(panelWeekStart);
      setList(data.list);
    } catch {
      setList(null);
    } finally {
      setLoading(false);
    }
  }, [panelWeekStart]);

  useEffect(() => {
    fetchList();
  }, [fetchList]);

  const handleGenerate = async () => {
    setActionLoading(true);
    try {
      await api.generateGroceryFromPlan(panelWeekStart, defaultListName(panelWeekStart));
      await fetchList();
    } finally {
      setActionLoading(false);
    }
  };

  const handleToggleItem = async (itemId, checked) => {
    setList(prev => ({
      ...prev,
      items: prev.items.map(i => i.id === itemId ? { ...i, checked } : i),
    }));
    try {
      await api.updateGroceryItem(list.id, itemId, { checked });
    } catch {
      await fetchList();
    }
  };

  const handleDeleteItem = async (itemId) => {
    setList(prev => ({ ...prev, items: prev.items.filter(i => i.id !== itemId) }));
    try {
      await api.deleteGroceryItem(list.id, itemId);
    } catch {
      await fetchList();
    }
  };

  const handleAddItem = async (e) => {
    e.preventDefault();
    const name = newItemName.trim();
    if (!name || !list) return;
    setNewItemName('');
    try {
      await api.addGroceryItem(list.id, { name });
      await fetchList();
    } catch {
      // Error surfaced via next fetch
    }
  };

  const handleClearList = async () => {
    if (!list || list.items.length === 0) return;
    setActionLoading(true);
    try {
      await Promise.all(list.items.map(i => api.deleteGroceryItem(list.id, i.id)));
      await fetchList();
    } finally {
      setActionLoading(false);
    }
  };

  if (collapsed) {
    return (
      <button
        onClick={() => setCollapsed(false)}
        className="hidden md:flex flex-col items-center gap-2 w-10 shrink-0 bg-surface rounded-2xl shadow-md py-4 text-warm-gray hover:text-terracotta transition-colors"
        aria-label="Expand grocery list"
      >
        <ChevronLeft size={16} />
        <span className="text-xs font-semibold tracking-wide [writing-mode:vertical-rl]">Grocery list</span>
      </button>
    );
  }

  return (
    <div className="w-full md:w-[280px] shrink-0 bg-surface rounded-2xl shadow-md p-4 flex flex-col gap-3 h-fit">
      <div className="flex items-center justify-between">
        <h3 className="text-lg font-bold text-brown font-serif">Grocery List</h3>
        <button
          onClick={() => setCollapsed(true)}
          className="hidden md:flex p-1.5 rounded-lg text-warm-gray hover:text-brown hover:bg-cream-dark transition-colors"
          aria-label="Collapse grocery list"
        >
          <ChevronRight size={16} />
        </button>
      </div>

      <div className="flex gap-1.5">
        {[{ label: 'This week', offset: 0 }, { label: 'Next week', offset: 1 }].map(({ label, offset }) => (
          <button
            key={offset}
            onClick={() => setWeekOffset(offset)}
            className={`flex-1 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors ${
              weekOffset === offset ? 'bg-terracotta text-white' : 'bg-cream text-brown-light hover:bg-cream-dark'
            }`}
          >
            {label}
          </button>
        ))}
      </div>

      {loading ? (
        <div className="flex justify-center py-8">
          <Spinner />
        </div>
      ) : !list ? (
        <div className="text-center py-6 space-y-3">
          <ShoppingCart size={28} className="mx-auto text-warm-gray/40" />
          <p className="text-sm text-warm-gray">
            Plan meals for the selected week to generate a shopping list.
          </p>
          {canGenerate && (
            <Button onClick={handleGenerate} disabled={actionLoading} className="w-full justify-center">
              {actionLoading ? <Spinner /> : <ShoppingCart size={16} />}
              Generate List
            </Button>
          )}
        </div>
      ) : (
        <>
          <div className="flex-1 -mx-1 max-h-[420px] overflow-y-auto">
            {list.items.length === 0 ? (
              <p className="text-sm text-warm-gray text-center py-4">No items yet</p>
            ) : (
              list.items.map(item => (
                <GroceryItem
                  key={item.id}
                  item={item}
                  onToggle={handleToggleItem}
                  onDelete={handleDeleteItem}
                />
              ))
            )}
          </div>

          <form onSubmit={handleAddItem} className="flex gap-1.5">
            <input
              type="text"
              value={newItemName}
              onChange={(e) => setNewItemName(e.target.value)}
              placeholder="Add item..."
              className="flex-1 px-3 py-2 text-sm rounded-lg border border-cream-dark bg-surface text-brown placeholder:text-warm-gray focus:outline-none focus:border-terracotta"
            />
            <button
              type="submit"
              disabled={!newItemName.trim()}
              className="p-2 rounded-lg bg-terracotta text-white disabled:opacity-40 hover:bg-terracotta/90 transition-colors"
              aria-label="Add item"
            >
              <Plus size={16} />
            </button>
          </form>

          <div className="flex items-center justify-between gap-2 pt-1 border-t border-cream-dark">
            <button
              onClick={() => navigate(`/grocery`)}
              className="text-xs text-warm-gray hover:text-terracotta transition-colors"
            >
              View full list
            </button>
            <div className="flex items-center gap-1">
              {canGenerate && (
                <button
                  onClick={handleGenerate}
                  disabled={actionLoading}
                  className="p-1.5 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-colors"
                  aria-label="Regenerate from meal plan"
                  title="Regenerate from meal plan"
                >
                  <RefreshCw size={14} />
                </button>
              )}
              <button
                onClick={handleClearList}
                disabled={actionLoading || list.items.length === 0}
                className="p-1.5 rounded-lg text-warm-gray hover:text-red-500 hover:bg-red-50 transition-colors disabled:opacity-40"
                aria-label="Clear list"
                title="Clear list"
              >
                <Trash2 size={14} />
              </button>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
