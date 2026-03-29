import React from 'react';

export default function NutritionFacts({ nutrition }) {
  if (!nutrition) return null;

  const { calories, protein, carbs, fat, fiber, sugar } = nutrition;

  // Only render if at least one field has a value
  const hasData = [calories, protein, carbs, fat, fiber, sugar].some(v => v !== null && v !== undefined && v !== '');
  if (!hasData) return null;

  const rows = [
    { label: 'Calories', value: calories, unit: 'kcal' },
    { label: 'Protein', value: protein, unit: 'g' },
    { label: 'Carbohydrates', value: carbs, unit: 'g' },
    { label: 'Fat', value: fat, unit: 'g' },
    { label: 'Fiber', value: fiber, unit: 'g' },
    { label: 'Sugar', value: sugar, unit: 'g' },
  ].filter(r => r.value !== null && r.value !== undefined && r.value !== '');

  return (
    <div className="bg-surface rounded-2xl shadow-md p-6 mt-8">
      <h2 className="text-xl font-bold text-brown mb-1 font-serif">Nutrition Facts</h2>
      <p className="text-xs text-warm-gray mb-3">Per serving</p>
      <div className="border-t-8 border-brown">
        {rows.map((row, i) => (
          <div
            key={row.label}
            className={`flex justify-between py-1.5 text-sm ${
              i < rows.length - 1 ? 'border-b border-cream-dark' : ''
            }`}
          >
            <span className={`${i === 0 ? 'font-bold text-brown' : 'text-brown-light'}`}>
              {row.label}
            </span>
            <span className="font-semibold text-brown">
              {row.value}{row.unit}
            </span>
          </div>
        ))}
      </div>
    </div>
  );
}
