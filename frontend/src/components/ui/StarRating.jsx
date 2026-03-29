import React, { useState } from 'react';
import { Star } from 'lucide-react';

export default function StarRating({ value = 0, onChange = null, size = 'md', count = null }) {
  const [hoverValue, setHoverValue] = useState(0);
  const interactive = typeof onChange === 'function';
  const displayValue = hoverValue || value;

  const iconSize = size === 'sm' ? 14 : size === 'lg' ? 24 : 18;

  return (
    <div
      className="flex items-center gap-0.5"
      aria-label={!interactive ? `Rating: ${value} out of 5` : undefined}
    >
      {[1, 2, 3, 4, 5].map((star) => {
        const filled = displayValue >= star;
        const halfFilled = !filled && displayValue >= star - 0.5;
        const commonProps = {
          key: star,
          className: `
            ${interactive ? 'cursor-pointer hover:scale-110' : 'cursor-default'}
            transition-transform duration-150
            ${interactive ? 'p-1.5' : 'p-0'}
            ${filled || halfFilled ? 'text-amber-400' : 'text-warm-gray/60'}
          `,
          style: { background: 'none', border: 'none', ...(interactive ? {} : { minWidth: 'auto', minHeight: 'auto' }) },
        };

        const starIcon = (
          <Star
            size={iconSize}
            fill={filled ? 'currentColor' : halfFilled ? 'currentColor' : 'none'}
            strokeWidth={filled || halfFilled ? 0 : 1.5}
          />
        );

        if (interactive) {
          return (
            <button
              {...commonProps}
              type="button"
              onClick={() => onChange(star)}
              onMouseEnter={() => setHoverValue(star)}
              onMouseLeave={() => setHoverValue(0)}
              aria-label={`${star} star${star !== 1 ? 's' : ''}`}
            >
              {starIcon}
            </button>
          );
        }

        return (
          <span
            {...commonProps}
            aria-hidden="true"
          >
            {starIcon}
          </span>
        );
      })}
      {count !== null && count !== undefined && (
        <span className={`ml-1 text-warm-gray ${size === 'sm' ? 'text-xs' : 'text-sm'}`}>
          ({count})
        </span>
      )}
    </div>
  );
}
