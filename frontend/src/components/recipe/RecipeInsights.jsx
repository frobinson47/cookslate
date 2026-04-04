import React, { useState, useEffect } from 'react';
import { DollarSign, Flame, TrendingUp, ChevronDown, ChevronUp } from 'lucide-react';
import * as api from '../../services/api';

export default function RecipeInsights({ recipeId }) {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [expanded, setExpanded] = useState(false);

  useEffect(() => {
    api.analyzeRecipe(recipeId)
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false));
  }, [recipeId]);

  if (loading || !data || data.coverage?.percent === 0) return null;

  const { cost, nutrition, coverage } = data;
  const perServing = nutrition.per_serving;

  return (
    <div className="mt-8">
      <button
        onClick={() => setExpanded(!expanded)}
        className="flex items-center gap-2 text-lg font-bold text-brown font-serif mb-4 hover:text-terracotta transition-colors"
      >
        <TrendingUp size={20} className="text-terracotta" />
        Recipe Insights
        {expanded ? <ChevronUp size={18} /> : <ChevronDown size={18} />}
      </button>

      {expanded && (
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {/* Cost Card */}
          {cost.total > 0 && (
            <div className="bg-surface rounded-xl p-5 shadow-sm border border-cream-dark">
              <div className="flex items-center gap-2 mb-3">
                <div className="w-8 h-8 rounded-lg bg-sage/10 flex items-center justify-center">
                  <DollarSign size={16} className="text-sage" />
                </div>
                <h3 className="text-sm font-bold text-brown">Estimated Cost</h3>
              </div>
              <div className="flex items-baseline gap-2">
                <span className="text-2xl font-bold text-brown">${cost.total.toFixed(2)}</span>
                <span className="text-sm text-warm-gray">total</span>
              </div>
              {cost.per_serving && (
                <p className="text-sm text-warm-gray mt-1">
                  ${cost.per_serving.toFixed(2)} per serving
                </p>
              )}
              <p className="text-xs text-warm-gray/60 mt-2">
                Based on US average grocery prices
              </p>
            </div>
          )}

          {/* Nutrition Card */}
          {nutrition.calories > 0 && (
            <div className="bg-surface rounded-xl p-5 shadow-sm border border-cream-dark">
              <div className="flex items-center gap-2 mb-3">
                <div className="w-8 h-8 rounded-lg bg-terracotta/10 flex items-center justify-center">
                  <Flame size={16} className="text-terracotta" />
                </div>
                <h3 className="text-sm font-bold text-brown">Nutrition Estimate</h3>
              </div>

              {perServing ? (
                <div>
                  <div className="flex items-baseline gap-2 mb-2">
                    <span className="text-2xl font-bold text-brown">{perServing.calories}</span>
                    <span className="text-sm text-warm-gray">cal / serving</span>
                  </div>
                  <div className="grid grid-cols-4 gap-2">
                    <div className="text-center">
                      <p className="text-sm font-bold text-brown">{perServing.protein}g</p>
                      <p className="text-[10px] text-warm-gray">Protein</p>
                    </div>
                    <div className="text-center">
                      <p className="text-sm font-bold text-brown">{perServing.carbs}g</p>
                      <p className="text-[10px] text-warm-gray">Carbs</p>
                    </div>
                    <div className="text-center">
                      <p className="text-sm font-bold text-brown">{perServing.fat}g</p>
                      <p className="text-[10px] text-warm-gray">Fat</p>
                    </div>
                    <div className="text-center">
                      <p className="text-sm font-bold text-brown">{perServing.fiber}g</p>
                      <p className="text-[10px] text-warm-gray">Fiber</p>
                    </div>
                  </div>
                </div>
              ) : (
                <div>
                  <div className="flex items-baseline gap-2">
                    <span className="text-2xl font-bold text-brown">{nutrition.calories}</span>
                    <span className="text-sm text-warm-gray">cal total</span>
                  </div>
                  <div className="flex gap-3 mt-2 text-sm text-warm-gray">
                    <span>{nutrition.protein}g protein</span>
                    <span>{nutrition.carbs}g carbs</span>
                    <span>{nutrition.fat}g fat</span>
                  </div>
                </div>
              )}

              <p className="text-xs text-warm-gray/60 mt-3">
                {coverage.percent}% of ingredients matched ({coverage.matched}/{coverage.total})
              </p>
            </div>
          )}

          {/* Unmatched ingredients */}
          {coverage.unmatched.length > 0 && (
            <div className="sm:col-span-2 text-xs text-warm-gray/60">
              Not in database: {coverage.unmatched.join(', ')}
            </div>
          )}
        </div>
      )}
    </div>
  );
}
