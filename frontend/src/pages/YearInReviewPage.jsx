import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { UtensilsCrossed, CalendarDays, ChefHat, Sparkles, Flame, Tag } from 'lucide-react';
import * as api from '../services/api';
import { thumbImageUrl } from '../utils/imageUrl';
import Spinner from '../components/ui/Spinner';
import EmptyState from '../components/ui/EmptyState';
import useDocumentTitle from '../hooks/useDocumentTitle';

const MONTH_NAMES = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December',
];

function formatMonth(yyyyMm) {
  if (!yyyyMm) return null;
  const [, month] = yyyyMm.split('-');
  return MONTH_NAMES[parseInt(month, 10) - 1];
}

function StatTile({ icon: Icon, value, label, color = 'text-terracotta' }) {
  return (
    <div className="bg-surface rounded-2xl shadow-md p-6 flex flex-col items-center text-center gap-1.5">
      <Icon size={28} className={color} />
      <span className="text-3xl font-bold text-brown font-display">{value}</span>
      <span className="text-sm text-warm-gray">{label}</span>
    </div>
  );
}

export default function YearInReviewPage() {
  useDocumentTitle('Year in Cooking');

  const currentYear = new Date().getFullYear();
  const [year, setYear] = useState(currentYear);
  const [review, setReview] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    setIsLoading(true);
    api.getYearInReview(year)
      .then(setReview)
      .catch(() => setReview(null))
      .finally(() => setIsLoading(false));
  }, [year]);

  const hasData = review && review.total_meals > 0;

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-brown flex items-center gap-2">
          <Sparkles size={24} className="text-terracotta" />
          Your {year} in Cooking
        </h1>
        <select
          value={year}
          onChange={(e) => setYear(parseInt(e.target.value, 10))}
          className="px-3 py-2 rounded-xl border border-cream-dark bg-surface text-brown text-sm focus:outline-none focus:border-terracotta"
        >
          {[currentYear, currentYear - 1, currentYear - 2].map((y) => (
            <option key={y} value={y}>{y}</option>
          ))}
        </select>
      </div>

      {isLoading && (
        <div className="flex justify-center py-20">
          <Spinner />
        </div>
      )}

      {!isLoading && !hasData && (
        <EmptyState
          icon={ChefHat}
          title={`No cooking logged for ${year}`}
          description="Mark recipes as cooked from the recipe page to build your year-in-review recap."
        />
      )}

      {!isLoading && hasData && (
        <>
          <div className="grid grid-cols-2 gap-4">
            <StatTile icon={UtensilsCrossed} value={review.total_meals} label="Meals Cooked" />
            <StatTile icon={ChefHat} value={review.unique_recipes} label="Unique Recipes" />
            <StatTile icon={Sparkles} value={review.new_recipes_tried} label="New Recipes Tried" color="text-sage" />
            <StatTile icon={Flame} value={review.streak_peak} label="Longest Streak (days)" color="text-sage" />
          </div>

          {review.most_active_month && (
            <div className="bg-surface rounded-2xl shadow-md p-6 flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-terracotta/10 flex items-center justify-center shrink-0">
                <CalendarDays size={22} className="text-terracotta" />
              </div>
              <div>
                <p className="font-bold text-brown">{formatMonth(review.most_active_month.month)}</p>
                <p className="text-sm text-warm-gray">
                  Your most active month — {review.most_active_month.count} meals cooked
                </p>
              </div>
            </div>
          )}

          {review.most_made_recipe && (
            <Link
              to={`/recipe/${review.most_made_recipe.id}`}
              className="group flex items-center gap-4 bg-surface rounded-2xl shadow-md p-4 hover:shadow-lg transition-shadow duration-200"
            >
              <div className="w-16 h-16 rounded-xl overflow-hidden bg-cream-dark shrink-0">
                {review.most_made_recipe.image_path ? (
                  <img
                    src={thumbImageUrl(review.most_made_recipe.image_path)}
                    alt=""
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <div className="w-full h-full flex items-center justify-center">
                    <ChefHat size={24} className="text-warm-gray/40" />
                  </div>
                )}
              </div>
              <div className="min-w-0">
                <p className="text-xs text-warm-gray uppercase tracking-wide">Most-Made Recipe</p>
                <p className="font-bold text-brown group-hover:text-terracotta transition-colors line-clamp-1">
                  {review.most_made_recipe.title}
                </p>
                <p className="text-sm text-warm-gray">Cooked {review.most_made_recipe.cook_count}× this year</p>
              </div>
            </Link>
          )}

          {review.top_tag && (
            <div className="bg-surface rounded-2xl shadow-md p-6 flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-sage/10 flex items-center justify-center shrink-0">
                <Tag size={22} className="text-sage" />
              </div>
              <div>
                <p className="font-bold text-brown">{review.top_tag.name}</p>
                <p className="text-sm text-warm-gray">
                  Your top tag — {review.top_tag.count} meals
                </p>
              </div>
            </div>
          )}
        </>
      )}
    </div>
  );
}
