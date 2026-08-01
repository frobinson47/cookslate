import React, { useState } from 'react';
import { CalendarPlus } from 'lucide-react';
import { useLicense } from '../../hooks/useLicense';
import { useToast } from '../../hooks/useToast';
import * as api from '../../services/api';
import MealSlotPicker from './MealSlotPicker';
import { DAY_NAMES_FULL, MEAL_TYPES } from '../../utils/mealPlan';

function getMonday(date) {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day + (day === 0 ? -6 : 1);
  d.setDate(diff);
  return d.toISOString().split('T')[0];
}

export default function AddToMealPlanButton({ recipeId, variant = 'overlay', className = '' }) {
  const { active: proActive } = useLicense();
  const [open, setOpen] = useState(false);
  const toast = useToast();

  if (!proActive) return null;

  const weekStart = getMonday(new Date());

  const handleAdd = async (dayIndex, mealType) => {
    await api.addMealPlanItem(recipeId, dayIndex, weekStart, mealType);
    const meal = MEAL_TYPES.find(m => m.value === mealType);
    toast.success(`Added to ${DAY_NAMES_FULL[dayIndex]} ${meal ? meal.label.toLowerCase() : ''}`);
  };

  const handleToggle = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setOpen(!open);
  };

  const handleClose = () => setOpen(false);

  if (variant === 'overlay') {
    return (
      <div className={`absolute bottom-2 right-2 z-10 ${className}`}>
        <button
          onClick={handleToggle}
          className="w-8 h-8 rounded-full bg-brown/70 text-white hover:bg-terracotta flex items-center justify-center transition-colors shadow-sm"
          aria-label="Add to meal plan"
          title="Add to meal plan"
        >
          <CalendarPlus size={14} />
        </button>
        {open && (
          <MealSlotPicker weekStart={weekStart} onAdd={handleAdd} onClose={handleClose} onError={() => toast.error('Failed to add to meal plan')} position="bottom-full right-0 mb-2" />
        )}
      </div>
    );
  }

  // variant === 'button' — for recipe detail page
  return (
    <div className={`relative inline-block ${className}`}>
      <button
        onClick={handleToggle}
        className="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-cream-dark bg-surface text-brown-light hover:bg-cream-dark hover:text-brown transition-colors duration-200 font-medium min-h-[44px]"
      >
        <CalendarPlus size={18} />
        Add to Meal Plan
      </button>
      {open && (
        <MealSlotPicker weekStart={weekStart} onAdd={handleAdd} onClose={handleClose} onError={() => toast.error('Failed to add to meal plan')} position="top-full left-0 mt-2" />
      )}
    </div>
  );
}
