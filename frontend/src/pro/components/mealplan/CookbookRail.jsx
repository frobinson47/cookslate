import React, { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { Search, Plus, BookOpen } from 'lucide-react';
import Spinner from '../../../components/ui/Spinner';
import MealSlotPicker from '../../../components/recipe/MealSlotPicker';
import { thumbImageUrl } from '../../../utils/imageUrl';
import * as api from '../../../services/api';

export default function CookbookRail({ weekStart, canAdd, onAdded }) {
  const [query, setQuery] = useState('');
  const [recipes, setRecipes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [openPickerFor, setOpenPickerFor] = useState(null);

  const searchTimerRef = useRef(null);

  useEffect(() => {
    setLoading(true);
    clearTimeout(searchTimerRef.current);
    searchTimerRef.current = setTimeout(async () => {
      try {
        const data = await api.getRecipes({ search: query, perPage: 30 });
        setRecipes(data.recipes || []);
      } catch {
        setRecipes([]);
      } finally {
        setLoading(false);
      }
    }, 300);
    return () => clearTimeout(searchTimerRef.current);
  }, [query]);

  const handleAdd = async (recipeId, dayIndex, mealType) => {
    await api.addMealPlanItem(recipeId, dayIndex, weekStart, mealType);
    onAdded?.();
  };

  return (
    <div className="w-full md:w-[280px] shrink-0 bg-surface rounded-2xl shadow-md p-4 flex flex-col gap-3">
      <div className="flex items-center gap-2">
        <BookOpen size={20} className="text-terracotta shrink-0" />
        <h3 className="text-lg font-bold text-brown font-serif">Your Cookbook</h3>
      </div>

      <div className="relative">
        <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-warm-gray" />
        <input
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder="Search cookbook..."
          className="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-cream-dark bg-surface text-brown placeholder:text-warm-gray focus:outline-none focus:border-terracotta"
        />
      </div>

      <div className="flex-1 -mx-1 max-h-[560px] overflow-y-auto space-y-1">
        {loading ? (
          <div className="flex justify-center py-8">
            <Spinner />
          </div>
        ) : recipes.length === 0 ? (
          <p className="text-sm text-warm-gray text-center py-4">No recipes found</p>
        ) : (
          recipes.map(recipe => (
            <div key={recipe.id} className="relative flex items-center gap-2.5 p-2 rounded-xl hover:bg-cream transition-colors group">
              <Link to={`/recipe/${recipe.id}`} className="flex items-center gap-2.5 flex-1 min-w-0">
                {recipe.image_path && (
                  <img
                    src={thumbImageUrl(recipe.image_path)}
                    alt={recipe.title}
                    loading="lazy"
                    className="w-10 h-10 rounded-lg object-cover shrink-0"
                  />
                )}
                <span className="text-sm font-medium text-brown truncate">{recipe.title}</span>
              </Link>
              {canAdd && (
                <div className="relative shrink-0">
                  <button
                    onClick={() => setOpenPickerFor(openPickerFor === recipe.id ? null : recipe.id)}
                    className="w-8 h-8 rounded-full text-warm-gray hover:text-terracotta hover:bg-terracotta/10 flex items-center justify-center transition-colors opacity-100 md:opacity-0 md:group-hover:opacity-100"
                    aria-label={`Add ${recipe.title} to meal plan`}
                    title="Add to meal plan"
                  >
                    <Plus size={16} />
                  </button>
                  {openPickerFor === recipe.id && (
                    <MealSlotPicker
                      weekStart={weekStart}
                      onAdd={(dayIndex, mealType) => handleAdd(recipe.id, dayIndex, mealType)}
                      onClose={() => setOpenPickerFor(null)}
                      position="top-full right-0 mt-1"
                    />
                  )}
                </div>
              )}
            </div>
          ))
        )}
      </div>
    </div>
  );
}
