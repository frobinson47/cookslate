import React, { useState } from 'react';
import { Check, ChevronLeft } from 'lucide-react';
import { DAY_NAMES, DAY_NAMES_FULL, MEAL_TYPES, getDayDate } from '../../utils/mealPlan';

/**
 * Day + meal-type picker popover for adding a recipe to a meal plan.
 * Caller owns the trigger button/open state; this owns the two-step
 * day → meal-type selection and the brief success confirmation.
 */
export default function MealSlotPicker({ weekStart, onAdd, onClose, onError, position = 'top-full left-0 mt-2' }) {
  const [selectedDay, setSelectedDay] = useState(null);
  const [adding, setAdding] = useState(false);
  const [success, setSuccess] = useState(null);

  const today = new Date();

  const handleSelectDay = (e, dayIndex) => {
    e.preventDefault();
    e.stopPropagation();
    setSelectedDay(dayIndex);
  };

  const handleAdd = async (e, mealType) => {
    e.preventDefault();
    e.stopPropagation();
    if (selectedDay === null) return;
    setAdding(true);
    try {
      await onAdd(selectedDay, mealType);
      const dayDate = getDayDate(weekStart, selectedDay);
      const meal = MEAL_TYPES.find(m => m.value === mealType);
      setSuccess(`${DAY_NAMES[selectedDay]} ${dayDate.getDate()} ${meal ? meal.label : ''}`);
      setTimeout(() => { onClose(); }, 1200);
    } catch {
      setAdding(false);
      onError?.();
    }
  };

  const handleBack = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setSelectedDay(null);
  };

  return (
    <>
      <div className="fixed inset-0 z-10" onClick={(e) => { e.preventDefault(); e.stopPropagation(); onClose(); }} />
      <div className={`absolute ${position} z-20 bg-surface rounded-xl shadow-lg border border-cream-dark p-2 min-w-[200px]`}>
        {success ? (
          <div className="flex items-center gap-2 px-3 py-2 text-sm text-sage font-medium">
            <Check size={14} />
            Added to {success}
          </div>
        ) : selectedDay === null ? (
          <>
            <p className="text-xs text-warm-gray px-2 py-1 font-semibold">Pick a day</p>
            <div className="grid grid-cols-7 gap-0.5 mt-1">
              {DAY_NAMES.map((name, idx) => {
                const dayDate = getDayDate(weekStart, idx);
                const isToday = today.toDateString() === dayDate.toDateString();
                return (
                  <button
                    key={idx}
                    onClick={(e) => handleSelectDay(e, idx)}
                    className={`flex flex-col items-center py-1.5 px-1 rounded-lg text-xs transition-colors hover:bg-terracotta/10 hover:text-terracotta ${
                      isToday ? 'text-terracotta font-bold' : 'text-brown'
                    }`}
                  >
                    <span className="font-semibold">{name.charAt(0)}</span>
                    <span>{dayDate.getDate()}</span>
                  </button>
                );
              })}
            </div>
          </>
        ) : (
          <>
            <div className="flex items-center gap-1 px-1 mb-1">
              <button
                onClick={handleBack}
                className="p-1 rounded-lg text-warm-gray hover:text-brown hover:bg-cream-dark transition-colors"
                aria-label="Back to day picker"
              >
                <ChevronLeft size={14} />
              </button>
              <p className="text-xs text-warm-gray font-semibold">
                {DAY_NAMES_FULL[selectedDay]} — which meal?
              </p>
            </div>
            <div className="space-y-0.5">
              {MEAL_TYPES.map((meal) => (
                <button
                  key={meal.value}
                  onClick={(e) => handleAdd(e, meal.value)}
                  disabled={adding}
                  className="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-brown hover:bg-terracotta/10 hover:text-terracotta transition-colors text-left"
                >
                  <meal.Icon size={16} />
                  <span className="font-medium">{meal.label}</span>
                </button>
              ))}
            </div>
          </>
        )}
      </div>
    </>
  );
}
