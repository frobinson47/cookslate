# Shoppable Grocery Lists + Pantry Tracking

*Convert recipe ingredient amounts into real store-buyable quantities and track what's already in your pantry.*

---

## 1. Overview

Two complementary features:

1. **Shoppable quantities** — When adding recipe ingredients to a grocery list, convert "1 tsp salt" into something meaningful: either "Salt — already in pantry" or "Salt (1 container, 26oz)" if you don't have it. Group similar items and round up to purchasable package sizes.

2. **Pantry tracking** — Mark ingredients you always have on hand (salt, pepper, olive oil, flour, etc.). These auto-mark as "in pantry" on future grocery lists so you know you don't need to buy them. Persistent across all lists.

---

## 2. Database Changes

### 2a. Add package info to ingredient_data

```sql
ALTER TABLE ingredient_data
  ADD COLUMN package_size DECIMAL(8,2) DEFAULT NULL,
  ADD COLUMN package_unit VARCHAR(50) DEFAULT NULL,
  ADD COLUMN package_description VARCHAR(100) DEFAULT NULL;
```

Examples:
| name | package_size | package_unit | package_description |
|------|-------------|-------------|-------------------|
| salt | 26 | oz | Container |
| butter | 16 | oz | 4-stick pack |
| flour | 5 | lb | Bag |
| olive oil | 16 | oz | Bottle |
| eggs | 12 | count | Dozen |
| chicken breast | 1 | lb | Package |
| milk | 1 | gallon | Jug |

### 2b. Pantry table

```sql
CREATE TABLE IF NOT EXISTS pantry (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  ingredient_name VARCHAR(255) NOT NULL,
  always_stocked BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_pantry (user_id, ingredient_name),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
```

Each user has their own pantry. `always_stocked = true` means "I always have this, don't add it to grocery lists." A future enhancement could track actual quantities, but for v1, it's just a yes/no flag.

### 2c. Add in_pantry flag to grocery_items

```sql
ALTER TABLE grocery_items
  ADD COLUMN in_pantry BOOLEAN DEFAULT FALSE;
```

When generating a grocery list from a recipe, items that match a pantry entry are auto-marked `in_pantry = true`. They still appear on the list (so the user can see what the recipe needs) but are visually distinct.

---

## 3. Shoppable Quantity Logic

### 3a. Conversion flow

When ingredients are added to a grocery list (from a recipe or meal plan):

1. **Match ingredient** to `ingredient_data` by fuzzy name match
2. **Convert amount** to the package unit (e.g., "100g flour" → 0.044 of a 5lb bag)
3. **Round up** to a sensible purchase quantity:
   - If need < 25% of a package → "you probably have this" (suggest pantry)
   - If need 25-100% → "1 package"
   - If need > 100% → "2 packages" etc.
4. **Display** both the recipe amount AND the store quantity:
   - Recipe: "100g flour"
   - Store: "Flour — 1 bag (5lb)" or "Already in pantry"

### 3b. Consolidation across recipes

When adding multiple recipes to the same grocery list, ingredients consolidate:
- Recipe A: 1 cup milk + Recipe B: 2 cups milk = 3 cups milk → "Milk — 1 gallon"
- This already partially works via the existing GroceryItem consolidation logic

### 3c. Smart defaults for unmatched ingredients

For ingredients not in `ingredient_data`:
- Show the raw recipe amount (current behavior)
- Don't try to convert — just pass through
- User can manually edit

---

## 4. Pantry Management

### 4a. Pantry page/section

A pantry management UI accessible from:
- Grocery page (as a tab or toggle)
- Settings page
- Or a standalone /pantry route

Shows a list of "always stocked" items with:
- Ingredient name
- Toggle to remove from pantry
- "Add to pantry" search input

### 4b. Quick-add from grocery list

On any grocery list item, a "Mark as pantry item" action:
- Adds the ingredient to the user's pantry
- Marks the current list item as `in_pantry`
- Future lists auto-mark this ingredient

### 4c. Auto-pantry suggestions

After a user marks the same ingredient as "in pantry" on 3+ grocery lists, suggest making it a permanent pantry item.

---

## 5. Grocery List UI Changes

### 5a. Item display

Each grocery item now shows:

**Normal item (not in pantry):**
```
☐ Butter — 1 pack (4 sticks)
  Recipe: 1/2 stick
```

**Pantry item (auto-detected or manually marked):**
```
☑ Salt — in pantry
  Recipe: 1 tsp
```
Dimmed/crossed-out styling, sorted to the bottom of each category group.

### 5b. New controls per item

- **Pantry toggle** (🏠 icon or checkbox) — marks item as "in pantry"
- Existing: check (bought), delete

### 5c. Grouped view enhancements

When grouped by aisle, pantry items appear at the bottom of each group with a "In Your Pantry" sub-header, or in their own "Already Have" section at the very bottom.

---

## 6. API Endpoints

### Pantry

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/pantry` | List user's pantry items |
| POST | `/pantry` | Add item to pantry `{ingredient_name}` |
| DELETE | `/pantry/{id}` | Remove item from pantry |

### Grocery enhancements

| Method | Endpoint | Change |
|--------|----------|--------|
| POST | `/grocery/{id}/recipes/{recipeId}` | Enhanced: checks pantry, converts to shoppable quantities |
| PUT | `/grocery/{id}/items/{itemId}` | Enhanced: accepts `in_pantry` field |

### Ingredient data

| Method | Endpoint | Change |
|--------|----------|--------|
| GET | `/ingredient-data` | Now includes package_size, package_unit, package_description |
| PUT | `/ingredient-data/{id}` | Now accepts package_size, package_unit, package_description |

---

## 7. Seed Data — Package Sizes

Update migration `010_ingredient_data.sql` (or new migration) to add package info:

```sql
UPDATE ingredient_data SET package_size = 26, package_unit = 'oz', package_description = 'Container' WHERE name = 'salt';
UPDATE ingredient_data SET package_size = 16, package_unit = 'oz', package_description = '4-stick pack' WHERE name = 'butter';
UPDATE ingredient_data SET package_size = 5, package_unit = 'lb', package_description = 'Bag' WHERE name = 'flour';
UPDATE ingredient_data SET package_size = 4, package_unit = 'lb', package_description = 'Bag' WHERE name = 'sugar';
UPDATE ingredient_data SET package_size = 16, package_unit = 'oz', package_description = 'Bottle' WHERE name = 'olive oil';
UPDATE ingredient_data SET package_size = 48, package_unit = 'oz', package_description = 'Bottle' WHERE name = 'vegetable oil';
UPDATE ingredient_data SET package_size = 12, package_unit = 'count', package_description = 'Dozen' WHERE name = 'egg';
UPDATE ingredient_data SET package_size = 1, package_unit = 'gallon', package_description = 'Jug' WHERE name = 'milk';
UPDATE ingredient_data SET package_size = 1, package_unit = 'pint', package_description = 'Carton' WHERE name = 'heavy cream';
UPDATE ingredient_data SET package_size = 16, package_unit = 'oz', package_description = 'Tub' WHERE name = 'sour cream';
UPDATE ingredient_data SET package_size = 8, package_unit = 'oz', package_description = 'Package' WHERE name = 'cream cheese';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Block' WHERE name = 'cheddar cheese';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Ball/bag' WHERE name = 'mozzarella';
UPDATE ingredient_data SET package_size = 32, package_unit = 'oz', package_description = 'Container' WHERE name = 'yogurt';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Package' WHERE name = 'chicken breast';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Package' WHERE name = 'ground beef';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Package' WHERE name = 'bacon';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Fillet' WHERE name = 'salmon';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Bag' WHERE name = 'shrimp';
UPDATE ingredient_data SET package_size = 2, package_unit = 'lb', package_description = 'Bag' WHERE name = 'rice';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Box' WHERE name = 'pasta';
UPDATE ingredient_data SET package_size = 1, package_unit = 'loaf', package_description = 'Loaf' WHERE name = 'bread';
UPDATE ingredient_data SET package_size = 32, package_unit = 'oz', package_description = 'Carton' WHERE name = 'chicken broth';
UPDATE ingredient_data SET package_size = 14.5, package_unit = 'oz', package_description = 'Can' WHERE name = 'diced tomatoes';
UPDATE ingredient_data SET package_size = 6, package_unit = 'oz', package_description = 'Can' WHERE name = 'tomato paste';
UPDATE ingredient_data SET package_size = 13.5, package_unit = 'oz', package_description = 'Can' WHERE name = 'coconut milk';
UPDATE ingredient_data SET package_size = 15, package_unit = 'oz', package_description = 'Can' WHERE name = 'black beans';
UPDATE ingredient_data SET package_size = 15, package_unit = 'oz', package_description = 'Bottle' WHERE name = 'soy sauce';
```

---

## 8. Implementation Phases

### Phase 1: Pantry (fastest value)
- Create pantry table + migration
- Pantry API endpoints (CRUD)
- Pantry UI (section on Grocery page or standalone)
- `in_pantry` flag on grocery items
- Auto-mark pantry items when adding recipes to grocery list

### Phase 2: Package sizes
- Add package columns to ingredient_data + seed data
- Update grocery list display to show "1 bag (5lb)" alongside recipe amount
- Package size management in Ingredient Database admin page

### Phase 3: Smart consolidation
- Enhance add-recipe-to-grocery to convert amounts to package units
- Round up to purchasable quantities
- Show "you probably have this" for small amounts of common pantry items

---

## 9. What's NOT in Scope

- Actual pantry inventory tracking with quantities ("I have 2 cups of flour left")
- Store-specific pricing/availability (Kroger API etc.)
- Barcode scanning to add to pantry (could use Open Food Facts later)
- Shared pantry between household users (v1 is per-user)
- Automatic pantry deduction when you cook a recipe

---

## 10. Success Criteria

1. User can mark salt, pepper, olive oil as "always stocked" once, and they auto-dim on every future grocery list
2. Grocery list shows "Butter — 1 pack (4 sticks)" instead of "1/2 stick butter"
3. Adding 3 recipes to a grocery list consolidates milk amounts and shows "Milk — 1 gallon"
4. Pantry items sort to the bottom of each aisle group with clear visual distinction
