import React from 'react';
import RecipeCard from './RecipeCard';
import { RecipeCardSkeleton } from '../ui/Skeleton';
import { BookOpen } from 'lucide-react';

export default function RecipeGrid({ recipes, isLoading, hasMore, onLoadMore }) {
  if (isLoading && recipes.length === 0) {
    return (
      <div className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        {[1, 2, 3, 4, 5, 6].map(i => (
          <RecipeCardSkeleton key={i} />
        ))}
      </div>
    );
  }

  if (!isLoading && recipes.length === 0) {
    return (
      <div className="text-center py-12">
        <BookOpen size={48} className="text-warm-gray mx-auto mb-4" />
        <p className="text-lg text-warm-gray">No recipes found</p>
        <p className="text-sm text-warm-gray mt-1">Try a different search or add a new recipe</p>
      </div>
    );
  }

  return (
    <div>
      <div className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6">
        {recipes.map((recipe) => (
          <RecipeCard key={recipe.id} recipe={recipe} />
        ))}
      </div>

      {/* Load more */}
      {hasMore && (
        <div className="flex justify-center mt-8 mb-4">
          <button
            onClick={onLoadMore}
            disabled={isLoading}
            className="px-8 py-4 bg-terracotta text-white font-bold rounded-xl hover:bg-terracotta-dark transition-colors duration-200 min-h-[48px] disabled:opacity-50 text-base"
          >
            {isLoading ? 'Loading...' : 'Load More Recipes'}
          </button>
        </div>
      )}
    </div>
  );
}
