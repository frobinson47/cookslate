import { describe, it, expect } from 'vitest';
import { parseIngredient, parseIngredientBlock } from './ingredientParser';

describe('parseIngredient', () => {
  it('parses basic ingredient', () => {
    expect(parseIngredient('2 cups flour')).toEqual({ amount: '2', unit: 'cup', name: 'flour' });
  });

  it('parses mixed number', () => {
    expect(parseIngredient('1 1/2 tsp salt')).toEqual({ amount: '1 1/2', unit: 'tsp', name: 'salt' });
  });

  it('handles ingredient with no amount', () => {
    expect(parseIngredient('salt and pepper to taste'))
      .toEqual({ amount: null, unit: null, name: 'salt and pepper to taste' });
  });

  it('parses parenthetical unit', () => {
    const r = parseIngredient('2 (14 oz) cans tomatoes');
    expect(r.amount).toBe('2');
    expect(r.unit).toBe('can');
    expect(r.name).toContain('tomatoes');
  });

  it('parses unicode half', () => {
    expect(parseIngredient('½ cup butter')).toEqual({ amount: '1/2', unit: 'cup', name: 'butter' });
  });

  it('parses unicode quarter', () => {
    expect(parseIngredient('¼ tsp cinnamon')).toEqual({ amount: '1/4', unit: 'tsp', name: 'cinnamon' });
  });

  it('parses unicode three-quarters', () => {
    expect(parseIngredient('¾ lb chicken')).toEqual({ amount: '3/4', unit: 'lb', name: 'chicken' });
  });

  it('parses spaced unicode mixed number', () => {
    expect(parseIngredient('1 ½ cups milk')).toEqual({ amount: '1 1/2', unit: 'cup', name: 'milk' });
  });

  it('parses unspaced unicode mixed number (regression: parity with PHP)', () => {
    expect(parseIngredient('1½ cups milk')).toEqual({ amount: '1 1/2', unit: 'cup', name: 'milk' });
  });

  it('parses fifth fractions (regression: missing in original port)', () => {
    expect(parseIngredient('⅖ cup sugar')).toEqual({ amount: '2/5', unit: 'cup', name: 'sugar' });
  });

  it('parses sixth fractions (regression: missing in original port)', () => {
    expect(parseIngredient('⅙ tsp nutmeg')).toEqual({ amount: '1/6', unit: 'tsp', name: 'nutmeg' });
  });

  it('parses a range', () => {
    expect(parseIngredient('2-3 tablespoons oil')).toEqual({ amount: '2-3', unit: 'tbsp', name: 'oil' });
  });

  it('parses amount with no unit', () => {
    expect(parseIngredient('3 eggs')).toEqual({ amount: '3', unit: null, name: 'eggs' });
  });

  it('handles empty string', () => {
    expect(parseIngredient('')).toEqual({ amount: null, unit: null, name: '' });
  });

  it('strips leading "of"', () => {
    expect(parseIngredient('2 cups of flour')).toEqual({ amount: '2', unit: 'cup', name: 'flour' });
  });
});

describe('parseIngredientBlock', () => {
  it('parses multiple lines and assigns sort_order', () => {
    const out = parseIngredientBlock('2 cups flour\n1 tsp salt\n\n3 eggs');
    expect(out).toHaveLength(3);
    expect(out[0]).toMatchObject({ amount: '2', unit: 'cup', name: 'flour', sort_order: 0 });
    expect(out[1]).toMatchObject({ amount: '1', unit: 'tsp', name: 'salt', sort_order: 1 });
    expect(out[2]).toMatchObject({ amount: '3', unit: null, name: 'eggs', sort_order: 2 });
  });

  it('returns empty array for blank input', () => {
    expect(parseIngredientBlock('   \n\n   ')).toEqual([]);
  });
});
