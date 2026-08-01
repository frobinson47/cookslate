import React, { useState, useEffect, useRef, useCallback, useMemo } from 'react';
import { ChevronLeft, ChevronRight, Plus, X, CalendarDays, Calendar, GripVertical, ArrowRight, Bookmark, BookmarkPlus, Trash2, LayoutGrid, List as ListIcon } from 'lucide-react';
import {
  DndContext,
  DragOverlay,
  closestCorners,
  KeyboardSensor,
  PointerSensor,
  TouchSensor,
  useSensor,
  useSensors,
  useDroppable,
} from '@dnd-kit/core';
import {
  SortableContext,
  verticalListSortingStrategy,
  useSortable,
  arrayMove,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import Button from '../../components/ui/Button';
import Modal from '../../components/ui/Modal';
import Spinner from '../../components/ui/Spinner';
import * as api from '../../services/api';
import useDocumentTitle from '../../hooks/useDocumentTitle';
import { thumbImageUrl } from '../../utils/imageUrl';
import { useAuth } from '../../hooks/useAuth';
import CookbookRail from '../components/mealplan/CookbookRail';
import GroceryPanel from '../components/mealplan/GroceryPanel';

// Date helpers
function getMonday(date) {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day + (day === 0 ? -6 : 1);
  d.setDate(diff);
  return d.toISOString().split('T')[0];
}

function formatDateRange(mondayStr) {
  const monday = new Date(mondayStr + 'T00:00:00');
  const sunday = new Date(monday);
  sunday.setDate(sunday.getDate() + 6);
  const opts = { month: 'short', day: 'numeric' };
  const yearOpts = { ...opts, year: 'numeric' };
  if (monday.getFullYear() !== new Date().getFullYear()) {
    return `${monday.toLocaleDateString('en-US', yearOpts)} – ${sunday.toLocaleDateString('en-US', yearOpts)}`;
  }
  return `${monday.toLocaleDateString('en-US', opts)} – ${sunday.toLocaleDateString('en-US', opts)}`;
}

function getDayDate(mondayStr, dayIndex) {
  const d = new Date(mondayStr + 'T00:00:00');
  d.setDate(d.getDate() + dayIndex);
  return d;
}

const DAY_NAMES = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
const DAY_NAMES_FULL = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

// Fixed meal-type slots every day gets. Items saved with no meal_type (from
// before this field existed, or added via the "Any" option) fall into the
// 'unassigned' bucket, which only renders when it has items.
const MEAL_SLOTS = [
  { key: 'breakfast', label: 'Breakfast', accent: 'border-l-sage' },
  { key: 'lunch', label: 'Lunch', accent: 'border-l-amber-400' },
  { key: 'dinner', label: 'Dinner', accent: 'border-l-terracotta' },
  { key: 'snack', label: 'Snack', accent: 'border-l-warm-gray' },
];
const UNASSIGNED_SLOT = { key: 'unassigned', label: 'Other', accent: 'border-l-cream-dark' };

function slotKeyForItem(item) {
  return item.meal_type || 'unassigned';
}

function getDefaultMobileDay(weekStart) {
  const today = new Date();
  const monday = new Date(weekStart + 'T00:00:00');
  const sunday = new Date(monday);
  sunday.setDate(sunday.getDate() + 6);
  if (today >= monday && today <= sunday) {
    const jsDay = today.getDay();
    return jsDay === 0 ? 6 : jsDay - 1;
  }
  return 0;
}

// --- Drag & Drop Components ---

function SortableMealItem({ item, onRemove, onMoveToDay, showMoveMenu, canModify = true }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: `item-${item.id}`, disabled: !canModify });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.4 : 1,
  };

  const [moveOpen, setMoveOpen] = useState(false);

  return (
    <div ref={setNodeRef} style={style} className="bg-cream rounded-xl group relative overflow-hidden">
      {/* Drag handle + action buttons bar */}
      <div className="flex items-center justify-between px-1.5 pt-1.5">
        {canModify ? (
          <button
            {...attributes}
            {...listeners}
            className="p-0.5 rounded text-warm-gray/50 hover:text-warm-gray cursor-grab active:cursor-grabbing touch-none shrink-0"
            aria-label="Drag to reorder"
          >
            <GripVertical size={14} />
          </button>
        ) : <span />}
        <div className="flex items-center gap-0.5 shrink-0">
        {showMoveMenu && canModify && (
          <div className="relative">
            <button
              onClick={() => setMoveOpen(!moveOpen)}
              className="p-1 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-colors duration-200 min-w-[28px] min-h-[28px] flex items-center justify-center"
              aria-label={`Move ${item.recipe.title} to another day`}
            >
              <ArrowRight size={14} />
            </button>
            {moveOpen && (
              <>
                <div className="fixed inset-0 z-10" onClick={() => setMoveOpen(false)} />
                <div className="absolute right-0 top-full mt-1 z-20 bg-surface rounded-xl shadow-lg border border-cream-dark p-1 min-w-[120px]">
                  {DAY_NAMES.map((name, idx) => (
                    idx !== item.day_of_week && (
                      <button
                        key={idx}
                        onClick={() => { onMoveToDay(item.id, idx); setMoveOpen(false); }}
                        className="w-full text-left px-3 py-2 text-sm text-brown hover:bg-cream rounded-lg transition-colors"
                      >
                        {DAY_NAMES_FULL[idx]}
                      </button>
                    )
                  ))}
                </div>
              </>
            )}
          </div>
        )}
        {canModify && (
          <button
            onClick={() => onRemove(item.id)}
            className="p-1 rounded-lg text-warm-gray hover:text-red-500 hover:bg-red-50 transition-colors duration-200 opacity-100 md:opacity-0 md:group-hover:opacity-100 min-w-[28px] min-h-[28px] flex items-center justify-center"
            aria-label={`Remove ${item.recipe.title}`}
          >
            <X size={16} />
          </button>
        )}
        </div>
      </div>
      {/* Stacked image + text */}
      {item.recipe.image_path && (
        <img
          src={thumbImageUrl(item.recipe.image_path)}
          alt={item.recipe.title}
          loading="lazy"
          className="w-full aspect-[4/3] object-cover"
        />
      )}
      <div className="px-2 py-1.5">
        <span className="text-xs font-medium text-brown leading-tight line-clamp-2">{item.recipe.title}</span>
      </div>
    </div>
  );
}

function DragOverlayCard({ item }) {
  if (!item) return null;
  return (
    <div className="bg-cream rounded-xl shadow-lg border border-terracotta/30 w-[160px] overflow-hidden">
      {item.recipe.image_path && (
        <img
          src={thumbImageUrl(item.recipe.image_path)}
          alt={item.recipe.title}
          className="w-full aspect-[4/3] object-cover"
        />
      )}
      <div className="px-2 py-1.5">
        <span className="text-xs font-medium text-brown line-clamp-2">{item.recipe.title}</span>
      </div>
    </div>
  );
}

// A single meal-type slot within a day: droppable + sortable list of items,
// a small header, and an "add to this slot" button. Used by both the grid
// (narrow column, slots stacked vertically) and list (wide row, slots side
// by side) views.
function MealTypeSlot({ dayIndex, slot, items, itemIds, onRemove, onMoveToDay, showMoveMenu = false, onAddClick, canAddOrManage, canModifyItems }) {
  const { setNodeRef, isOver } = useDroppable({ id: `day-${dayIndex}-${slot.key}` });

  return (
    <div className={`flex-1 min-w-0 border-l-2 ${slot.accent} pl-2`}>
      <div className="flex items-center justify-between mb-1.5">
        <p className="text-[11px] font-semibold uppercase tracking-wide text-warm-gray italic">{slot.label}</p>
        {canAddOrManage && (
          <button
            onClick={() => onAddClick(dayIndex, slot.key)}
            className="p-1 rounded-lg text-warm-gray hover:text-terracotta hover:bg-terracotta/10 transition-colors"
            aria-label={`Add ${slot.label.toLowerCase()} for ${DAY_NAMES_FULL[dayIndex]}`}
          >
            <Plus size={13} />
          </button>
        )}
      </div>
      <div
        ref={setNodeRef}
        className={`space-y-1.5 min-h-[48px] rounded-xl transition-colors duration-200 ${
          isOver ? 'bg-terracotta/5 ring-1 ring-terracotta/20' : ''
        }`}
      >
        <SortableContext items={itemIds} strategy={verticalListSortingStrategy}>
          {items.map(item => (
            <SortableMealItem
              key={item.id}
              item={item}
              onRemove={onRemove}
              onMoveToDay={onMoveToDay}
              showMoveMenu={showMoveMenu}
              canModify={canModifyItems}
            />
          ))}
        </SortableContext>
      </div>
    </div>
  );
}

// --- Main Component ---

export default function MealPlanPage() {
  useDocumentTitle('Meal Plan');

  const { user, isAdmin, isViewer } = useAuth();
  const [weekStart, setWeekStart] = useState(() => getMonday(new Date()));
  const [plan, setPlan] = useState(null);
  const [loading, setLoading] = useState(true);
  const [householdMembers, setHouseholdMembers] = useState([]);
  const [viewUserId, setViewUserId] = useState(null); // null = viewing own plan
  const [viewMode, setViewMode] = useState(() => localStorage.getItem('mealPlanViewMode') || 'grid');
  const [selectedDay, setSelectedDay] = useState(0);
  const [selectedMealType, setSelectedMealType] = useState(null);
  const [showRecipeSearch, setShowRecipeSearch] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);
  const [searchLoading, setSearchLoading] = useState(false);
  const [activeMobileDay, setActiveMobileDay] = useState(() => getDefaultMobileDay(getMonday(new Date())));
  const [activeId, setActiveId] = useState(null);
  const [showTemplates, setShowTemplates] = useState(false);
  const [templates, setTemplates] = useState([]);
  const [templatesLoading, setTemplatesLoading] = useState(false);
  const [showSaveTemplate, setShowSaveTemplate] = useState(false);
  const [templateName, setTemplateName] = useState('');
  const [templateActionLoading, setTemplateActionLoading] = useState(false);
  const [templateError, setTemplateError] = useState(null);

  const searchTimerRef = useRef(null);

  useEffect(() => {
    localStorage.setItem('mealPlanViewMode', viewMode);
  }, [viewMode]);

  // DnD sensors — pointer needs a small distance to avoid conflicts with clicks
  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(TouchSensor, { activationConstraint: { delay: 200, tolerance: 5 } }),
    useSensor(KeyboardSensor),
  );

  // Fetch plan on weekStart or viewed-member change
  const fetchPlan = useCallback(async () => {
    setLoading(true);
    try {
      const data = await api.getMealPlan(weekStart, viewUserId);
      setPlan(data);
    } catch {
      setPlan(null);
    } finally {
      setLoading(false);
    }
  }, [weekStart, viewUserId]);

  useEffect(() => {
    fetchPlan();
  }, [fetchPlan]);

  // Load household members once, for the "view another member's plan" switcher
  useEffect(() => {
    api.getHouseholdMembers()
      .then(data => setHouseholdMembers((data.members || []).filter(m => m.id !== user?.id)))
      .catch(() => setHouseholdMembers([]));
  }, [user?.id]);

  const isOwnWeek = viewUserId === null;
  // Adding items / templates always act on the caller's own plan
  // server-side, so they only make sense when viewing your own week.
  const canAddOrManage = isOwnWeek && !isViewer;
  // Removing/reordering an existing item is allowed for its owner or an
  // admin, even while browsing someone else's week.
  const canModifyItems = (plan?.is_owner !== false || isAdmin) && !isViewer;

  // Reset mobile day when week changes
  useEffect(() => {
    setActiveMobileDay(getDefaultMobileDay(weekStart));
  }, [weekStart]);

  // Debounced recipe search (used by the per-slot "+" in the center plan)
  useEffect(() => {
    if (!searchQuery.trim()) {
      setSearchResults([]);
      return;
    }
    clearTimeout(searchTimerRef.current);
    searchTimerRef.current = setTimeout(async () => {
      setSearchLoading(true);
      try {
        const data = await api.getRecipes({ search: searchQuery, perPage: 10 });
        setSearchResults(data.recipes || []);
      } catch {
        setSearchResults([]);
      } finally {
        setSearchLoading(false);
      }
    }, 300);
    return () => clearTimeout(searchTimerRef.current);
  }, [searchQuery]);

  // Get items for a specific (day, meal-type slot)
  const getItemsForSlot = useCallback((dayIndex, slotKey) => {
    if (!plan || !plan.items) return [];
    return plan.items
      .filter(item => item.day_of_week === dayIndex && slotKeyForItem(item) === slotKey)
      .sort((a, b) => a.sort_order - b.sort_order);
  }, [plan]);

  // Which slots to render for a given day: the 4 fixed ones, plus
  // "unassigned" only if legacy items without a meal_type live there.
  const slotsForDay = useCallback((dayIndex) => {
    const hasUnassigned = (plan?.items || []).some(
      i => i.day_of_week === dayIndex && slotKeyForItem(i) === 'unassigned'
    );
    return hasUnassigned ? [...MEAL_SLOTS, UNASSIGNED_SLOT] : MEAL_SLOTS;
  }, [plan]);

  // Build sortable IDs per (day, slot) for DnD context
  const slotItemIds = useMemo(() => {
    const result = {};
    for (let d = 0; d < 7; d++) {
      for (const slot of [...MEAL_SLOTS, UNASSIGNED_SLOT]) {
        result[`${d}-${slot.key}`] = getItemsForSlot(d, slot.key).map(item => `item-${item.id}`);
      }
    }
    return result;
  }, [getItemsForSlot]);

  // Find which (day, slot) an item belongs to
  const findSlotForItem = useCallback((itemId) => {
    if (!plan || !plan.items) return null;
    const numId = parseInt(itemId.replace('item-', ''));
    const item = plan.items.find(i => i.id === numId);
    return item ? { day: item.day_of_week, slotKey: slotKeyForItem(item) } : null;
  }, [plan]);

  // Parse a droppable/sortable id into { day, slotKey } — either a slot
  // container id ("day-3-lunch") or an item id (look up its current slot).
  const resolveDropTarget = useCallback((id) => {
    const match = /^day-(\d)-(.+)$/.exec(String(id));
    if (match) {
      return { day: parseInt(match[1], 10), slotKey: match[2] };
    }
    return findSlotForItem(id);
  }, [findSlotForItem]);

  // Active drag item for overlay
  const activeItem = useMemo(() => {
    if (!activeId || !plan) return null;
    const numId = parseInt(activeId.replace('item-', ''));
    return plan.items.find(i => i.id === numId) || null;
  }, [activeId, plan]);

  // --- DnD Handlers ---

  const handleDragStart = (event) => {
    if (!canModifyItems) return;
    setActiveId(event.active.id);
  };

  const handleDragOver = (event) => {
    if (!canModifyItems) return;
    const { active, over } = event;
    if (!over || !plan) return;

    const from = resolveDropTarget(active.id);
    const to = resolveDropTarget(over.id);
    if (!from || !to || (from.day === to.day && from.slotKey === to.slotKey)) return;

    const numId = parseInt(String(active.id).replace('item-', ''));
    setPlan(prev => ({
      ...prev,
      items: prev.items.map(item =>
        item.id === numId
          ? { ...item, day_of_week: to.day, meal_type: to.slotKey === 'unassigned' ? null : to.slotKey, sort_order: 999 }
          : item
      ),
    }));
  };

  const handleDragEnd = async (event) => {
    const { active, over } = event;
    setActiveId(null);

    if (!canModifyItems || !over || !plan) return;

    const target = resolveDropTarget(over.id);
    if (!target) return;

    const { day: targetDay, slotKey: targetSlot } = target;
    const targetMealType = targetSlot === 'unassigned' ? null : targetSlot;

    // Get the items for the target slot (current state) and compute final order
    const slotItems = plan.items
      .filter(i => i.day_of_week === targetDay && slotKeyForItem(i) === targetSlot)
      .sort((a, b) => a.sort_order - b.sort_order);

    const itemIds = slotItems.map(i => `item-${i.id}`);
    const oldIndex = itemIds.indexOf(active.id);
    let newIndex = itemIds.indexOf(String(over.id));

    let finalOrder;
    if (oldIndex !== -1 && newIndex !== -1 && oldIndex !== newIndex) {
      finalOrder = arrayMove(itemIds, oldIndex, newIndex);
    } else {
      finalOrder = itemIds;
    }

    // Update local state with new sort_order values
    const updates = {};
    finalOrder.forEach((id, index) => {
      const itemNum = parseInt(id.replace('item-', ''));
      updates[itemNum] = index;
    });

    setPlan(prev => ({
      ...prev,
      items: prev.items.map(item =>
        updates[item.id] !== undefined
          ? { ...item, day_of_week: targetDay, meal_type: targetMealType, sort_order: updates[item.id] }
          : item
      ),
    }));

    // Persist to backend
    try {
      await Promise.all(
        Object.entries(updates).map(([itemId, sortOrder]) =>
          api.updateMealPlanItem(parseInt(itemId), {
            day_of_week: targetDay,
            meal_type: targetMealType,
            sort_order: sortOrder,
          })
        )
      );
    } catch {
      // Revert on failure
      await fetchPlan();
    }
  };

  // Move item to a different day (mobile menu) — keeps its current meal type
  const handleMoveToDay = async (itemId, newDay) => {
    if (!canModifyItems) return;
    const item = plan.items.find(i => i.id === itemId);
    const slotKey = item ? slotKeyForItem(item) : 'unassigned';
    const targetSlotItems = getItemsForSlot(newDay, slotKey);
    const newSortOrder = targetSlotItems.length > 0
      ? Math.max(...targetSlotItems.map(i => i.sort_order)) + 1
      : 0;

    // Optimistic update
    setPlan(prev => ({
      ...prev,
      items: prev.items.map(i =>
        i.id === itemId
          ? { ...i, day_of_week: newDay, sort_order: newSortOrder }
          : i
      ),
    }));

    try {
      await api.updateMealPlanItem(itemId, {
        day_of_week: newDay,
        sort_order: newSortOrder,
      });
    } catch {
      await fetchPlan();
    }
  };

  // Week navigation
  const goToPreviousWeek = () => {
    const d = new Date(weekStart + 'T00:00:00');
    d.setDate(d.getDate() - 7);
    setWeekStart(d.toISOString().split('T')[0]);
  };

  const goToNextWeek = () => {
    const d = new Date(weekStart + 'T00:00:00');
    d.setDate(d.getDate() + 7);
    setWeekStart(d.toISOString().split('T')[0]);
  };

  const goToCurrentWeek = () => {
    setWeekStart(getMonday(new Date()));
  };

  // Add recipe to a specific (day, meal-type) slot
  const handleOpenSearch = (dayIndex, mealType) => {
    if (!canAddOrManage) return;
    setSelectedDay(dayIndex);
    setSelectedMealType(mealType);
    setSearchQuery('');
    setSearchResults([]);
    setShowRecipeSearch(true);
  };

  const handleAddRecipe = async (recipeId) => {
    if (!canAddOrManage) return;
    try {
      await api.addMealPlanItem(recipeId, selectedDay, weekStart, selectedMealType);
      setShowRecipeSearch(false);
      setSearchQuery('');
      setSearchResults([]);
      await fetchPlan();
    } catch {
      // Error handled by api layer
    }
  };

  // Remove recipe
  const handleRemove = async (itemId) => {
    if (!canModifyItems) return;
    try {
      await api.removeMealPlanItem(itemId);
      await fetchPlan();
    } catch {
      // Error handled by api layer
    }
  };

  // Meal plan templates
  const handleOpenTemplates = async () => {
    setShowTemplates(true);
    setTemplateError(null);
    setTemplatesLoading(true);
    try {
      const data = await api.getMealPlanTemplates();
      setTemplates(data.templates || []);
    } catch {
      setTemplates([]);
    } finally {
      setTemplatesLoading(false);
    }
  };

  const handleOpenSaveTemplate = () => {
    if (!canAddOrManage) return;
    setTemplateName(`${formatDateRange(weekStart)} Plan`);
    setTemplateError(null);
    setShowSaveTemplate(true);
  };

  const handleSaveTemplate = async () => {
    setTemplateActionLoading(true);
    setTemplateError(null);
    try {
      await api.saveMealPlanTemplate(weekStart, templateName.trim());
      setShowSaveTemplate(false);
    } catch (err) {
      setTemplateError(err?.message || 'Could not save this week as a template.');
    } finally {
      setTemplateActionLoading(false);
    }
  };

  const handleApplyTemplate = async (templateId) => {
    if (!canAddOrManage) return;
    if (plan?.items?.length > 0 && !window.confirm(`Apply this template to ${formatDateRange(weekStart)}? This replaces the current meals for that week.`)) {
      return;
    }
    setTemplateActionLoading(true);
    setTemplateError(null);
    try {
      const data = await api.applyMealPlanTemplate(templateId, weekStart);
      setPlan(data);
      setShowTemplates(false);
    } catch (err) {
      setTemplateError(err?.message || 'Could not apply that template.');
    } finally {
      setTemplateActionLoading(false);
    }
  };

  const handleDeleteTemplate = async (templateId) => {
    setTemplateActionLoading(true);
    try {
      await api.deleteMealPlanTemplate(templateId);
      setTemplates(prev => prev.filter(t => t.id !== templateId));
    } catch {
      // Error surfaced via templateError below on next action
    } finally {
      setTemplateActionLoading(false);
    }
  };

  const slotAddButtonLabel = selectedMealType
    ? MEAL_SLOTS.find(s => s.key === selectedMealType)?.label || 'Meal'
    : 'Meal';

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div className="flex items-center gap-2">
          <CalendarDays size={28} className="text-terracotta shrink-0" />
          <h1 className="text-2xl md:text-3xl font-bold text-brown font-serif">Meal Plan</h1>
        </div>
        <div className="flex items-center gap-2 flex-wrap">
          {householdMembers.length > 0 && (
            <select
              value={viewUserId ?? ''}
              onChange={(e) => setViewUserId(e.target.value ? parseInt(e.target.value, 10) : null)}
              className="px-3 py-2 text-sm rounded-xl border border-cream-dark bg-surface text-brown focus:outline-none focus:border-terracotta min-h-[44px]"
            >
              <option value="">My plan</option>
              {householdMembers.map(m => (
                <option key={m.id} value={m.id}>{m.username}'s plan</option>
              ))}
            </select>
          )}
          <a
            href={`/api/meal-plan/ical?week=${weekStart}`}
            className="flex items-center gap-2 px-3 py-2 text-sm text-warm-gray hover:text-brown hover:bg-cream-dark rounded-xl transition-colors min-h-[44px]"
            title="Export to calendar"
          >
            <Calendar size={16} />
            iCal
          </a>
          {canAddOrManage && (
            <>
              <Button variant="secondary" onClick={handleOpenTemplates}>
                <Bookmark size={18} />
                Templates
              </Button>
              <Button
                variant="secondary"
                onClick={handleOpenSaveTemplate}
                disabled={!plan || !plan.items || plan.items.length === 0}
              >
                <BookmarkPlus size={18} />
                Save as Template
              </Button>
            </>
          )}
        </div>
      </div>

      {!isOwnWeek && (
        <p className="text-sm text-warm-gray -mt-4">
          Viewing {householdMembers.find(m => m.id === viewUserId)?.username}'s plan — view only{isAdmin ? ' (you can still remove or reorder their items as an admin)' : ''}.
        </p>
      )}

      {/* Week navigation + view toggle */}
      <div className="flex items-center justify-between bg-surface rounded-2xl shadow-md px-4 py-3">
        <button
          onClick={goToPreviousWeek}
          className="p-2 rounded-xl text-brown-light hover:bg-cream-dark transition-colors duration-200 min-w-[44px] min-h-[44px] flex items-center justify-center"
          aria-label="Previous week"
        >
          <ChevronLeft size={20} />
        </button>
        <h2 className="text-lg md:text-xl font-bold text-brown font-serif">
          {formatDateRange(weekStart)}
        </h2>
        <div className="flex items-center gap-1">
          <button
            onClick={goToCurrentWeek}
            className="px-3 py-1.5 rounded-xl text-sm font-semibold text-terracotta hover:bg-terracotta/10 transition-colors duration-200 min-h-[44px]"
          >
            Today
          </button>
          <button
            onClick={goToNextWeek}
            className="p-2 rounded-xl text-brown-light hover:bg-cream-dark transition-colors duration-200 min-w-[44px] min-h-[44px] flex items-center justify-center"
            aria-label="Next week"
          >
            <ChevronRight size={20} />
          </button>
          <div className="hidden md:flex items-center gap-0.5 ml-2 pl-2 border-l border-cream-dark">
            <button
              onClick={() => setViewMode('grid')}
              className={`p-2 rounded-lg transition-colors ${viewMode === 'grid' ? 'bg-terracotta text-white' : 'text-warm-gray hover:bg-cream-dark'}`}
              aria-label="Grid view"
              title="Grid view"
            >
              <LayoutGrid size={16} />
            </button>
            <button
              onClick={() => setViewMode('list')}
              className={`p-2 rounded-lg transition-colors ${viewMode === 'list' ? 'bg-terracotta text-white' : 'text-warm-gray hover:bg-cream-dark'}`}
              aria-label="List view"
              title="List view"
            >
              <ListIcon size={16} />
            </button>
          </div>
        </div>
      </div>

      {/* 3-panel layout: cookbook rail | plan | grocery panel */}
      <div className="flex flex-col md:flex-row gap-4 items-start">
        {canAddOrManage && (
          <CookbookRail weekStart={weekStart} canAdd={canAddOrManage} onAdded={fetchPlan} />
        )}

        <div className="flex-1 min-w-0 w-full">
          {loading ? (
            <div className="flex justify-center py-12">
              <Spinner />
            </div>
          ) : (
            <DndContext
              sensors={sensors}
              collisionDetection={closestCorners}
              onDragStart={handleDragStart}
              onDragOver={handleDragOver}
              onDragEnd={handleDragEnd}
            >
              {/* Desktop grid view: 7 columns, meal-type slots stacked within each day */}
              {viewMode === 'grid' && (
                <div className="hidden md:grid grid-cols-7 gap-3">
                  {DAY_NAMES.map((dayName, dayIndex) => {
                    const dayDate = getDayDate(weekStart, dayIndex);
                    const isToday = new Date().toDateString() === dayDate.toDateString();

                    return (
                      <div
                        key={dayIndex}
                        className={`bg-surface rounded-2xl shadow-md p-3 flex flex-col gap-3 min-h-[280px] ${isToday ? 'ring-2 ring-terracotta/30' : ''}`}
                      >
                        <div className="text-center pb-2 border-b border-cream-dark">
                          <p className={`text-xs font-semibold uppercase tracking-wide ${isToday ? 'text-terracotta' : 'text-warm-gray'}`}>
                            {dayName}
                          </p>
                          <p className={`text-lg font-bold ${isToday ? 'text-terracotta' : 'text-brown'}`}>
                            {dayDate.getDate()}
                          </p>
                        </div>

                        {slotsForDay(dayIndex).map(slot => (
                          <MealTypeSlot
                            key={slot.key}
                            dayIndex={dayIndex}
                            slot={slot}
                            items={getItemsForSlot(dayIndex, slot.key)}
                            itemIds={slotItemIds[`${dayIndex}-${slot.key}`]}
                            onRemove={handleRemove}
                            onAddClick={handleOpenSearch}
                            canAddOrManage={canAddOrManage}
                            canModifyItems={canModifyItems}
                          />
                        ))}
                      </div>
                    );
                  })}
                </div>
              )}

              {/* Desktop list view: stacked day cards, meal-type slots side by side */}
              {viewMode === 'list' && (
                <div className="hidden md:flex md:flex-col gap-3">
                  {DAY_NAMES.map((dayName, dayIndex) => {
                    const dayDate = getDayDate(weekStart, dayIndex);
                    const isToday = new Date().toDateString() === dayDate.toDateString();

                    return (
                      <div
                        key={dayIndex}
                        className={`bg-surface rounded-2xl shadow-md p-4 ${isToday ? 'ring-2 ring-terracotta/30' : ''}`}
                      >
                        <div className="flex items-center gap-2 mb-3 pb-2 border-b border-cream-dark">
                          <span className={`text-xs font-semibold uppercase tracking-wide ${isToday ? 'text-terracotta' : 'text-warm-gray'}`}>
                            {dayName}
                          </span>
                          <span className={`text-lg font-bold ${isToday ? 'text-terracotta' : 'text-brown'}`}>
                            {dayDate.getDate()}
                          </span>
                          {isToday && (
                            <span className="px-2 py-0.5 rounded-full bg-terracotta text-white text-[10px] font-bold uppercase tracking-wide">
                              Today
                            </span>
                          )}
                        </div>
                        <div className="flex gap-4">
                          {slotsForDay(dayIndex).map(slot => (
                            <MealTypeSlot
                              key={slot.key}
                              dayIndex={dayIndex}
                              slot={slot}
                              items={getItemsForSlot(dayIndex, slot.key)}
                              itemIds={slotItemIds[`${dayIndex}-${slot.key}`]}
                              onRemove={handleRemove}
                              onAddClick={handleOpenSearch}
                              canAddOrManage={canAddOrManage}
                              canModifyItems={canModifyItems}
                            />
                          ))}
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}

              {/* Mobile: day picker + single day view, meal-type slots stacked */}
              <div className="md:hidden space-y-4">
                <div className="flex items-center justify-around bg-surface rounded-2xl shadow-md p-2">
                  {DAY_NAMES.map((dayName, dayIndex) => {
                    const dayDate = getDayDate(weekStart, dayIndex);
                    const isActive = activeMobileDay === dayIndex;
                    const isToday = new Date().toDateString() === dayDate.toDateString();
                    const hasItems = (plan?.items || []).some(i => i.day_of_week === dayIndex);

                    return (
                      <button
                        key={dayIndex}
                        onClick={() => setActiveMobileDay(dayIndex)}
                        className={`flex flex-col items-center gap-0.5 px-2 py-2 rounded-xl min-w-[44px] min-h-[44px] transition-colors duration-200 ${
                          isActive
                            ? 'bg-terracotta text-white'
                            : isToday
                              ? 'text-terracotta'
                              : 'text-brown-light'
                        }`}
                      >
                        <span className="text-xs font-semibold">{dayName.charAt(0)}</span>
                        <span className="text-sm font-bold">{dayDate.getDate()}</span>
                        {hasItems && !isActive && (
                          <span className="w-1.5 h-1.5 rounded-full bg-terracotta" />
                        )}
                      </button>
                    );
                  })}
                </div>

                <div className="bg-surface rounded-2xl shadow-md p-4 space-y-3">
                  <h3 className="text-lg font-bold text-brown font-serif mb-1">
                    {DAY_NAMES_FULL[activeMobileDay]}, {getDayDate(weekStart, activeMobileDay).toLocaleDateString('en-US', { month: 'long', day: 'numeric' })}
                  </h3>

                  {slotsForDay(activeMobileDay).map(slot => (
                    <MealTypeSlot
                      key={slot.key}
                      dayIndex={activeMobileDay}
                      slot={slot}
                      items={getItemsForSlot(activeMobileDay, slot.key)}
                      itemIds={slotItemIds[`${activeMobileDay}-${slot.key}`]}
                      onRemove={handleRemove}
                      onMoveToDay={handleMoveToDay}
                      showMoveMenu={canModifyItems}
                      onAddClick={handleOpenSearch}
                      canAddOrManage={canAddOrManage}
                      canModifyItems={canModifyItems}
                    />
                  ))}
                </div>
              </div>

              <DragOverlay>
                <DragOverlayCard item={activeItem} />
              </DragOverlay>
            </DndContext>
          )}
        </div>

        <GroceryPanel canGenerate={canAddOrManage} />
      </div>

      {/* Recipe search modal — day & meal type are already chosen via the slot's "+" */}
      <Modal
        isOpen={showRecipeSearch}
        onClose={() => setShowRecipeSearch(false)}
        title={`Add ${slotAddButtonLabel} — ${DAY_NAMES_FULL[selectedDay]}`}
        size="lg"
      >
        <input
          type="text"
          placeholder="Search recipes..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="w-full px-4 py-2.5 rounded-xl border border-cream-dark bg-surface text-brown placeholder:text-warm-gray focus:outline-none focus:border-terracotta focus:ring-1 focus:ring-terracotta transition-colors duration-200"
          autoFocus
        />
        <div className="mt-3 max-h-96 overflow-y-auto space-y-2">
          {searchLoading ? (
            <div className="flex justify-center py-4">
              <Spinner />
            </div>
          ) : searchResults.length === 0 && searchQuery ? (
            <p className="text-warm-gray text-center py-4">No recipes found</p>
          ) : (
            searchResults.map(recipe => (
              <button
                key={recipe.id}
                onClick={() => handleAddRecipe(recipe.id)}
                className="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-cream transition-colors text-left min-h-[44px]"
              >
                {recipe.image_path && (
                  <img
                    src={thumbImageUrl(recipe.image_path)}
                    alt={recipe.title}
                    loading="lazy"
                    className="w-12 h-12 rounded-lg object-cover shrink-0"
                  />
                )}
                <div className="flex-1 min-w-0">
                  <p className="font-medium text-brown truncate">{recipe.title}</p>
                  {recipe.description && (
                    <p className="text-sm text-warm-gray truncate">{recipe.description}</p>
                  )}
                </div>
              </button>
            ))
          )}
        </div>
      </Modal>

      {/* Save as template modal */}
      <Modal isOpen={showSaveTemplate} onClose={() => setShowSaveTemplate(false)} title="Save as Template" size="sm">
        <div className="space-y-4">
          <p className="text-brown-light text-sm">
            Save this week's meal plan as a reusable template you can apply to any future week.
          </p>
          <div>
            <label className="block text-sm font-semibold text-brown mb-1">Template Name</label>
            <input
              type="text"
              value={templateName}
              onChange={(e) => setTemplateName(e.target.value)}
              className="w-full px-4 py-2.5 rounded-xl border border-cream-dark bg-surface text-brown placeholder:text-warm-gray focus:outline-none focus:border-terracotta focus:ring-1 focus:ring-terracotta transition-colors duration-200"
              autoFocus
            />
          </div>
          {templateError && <p className="text-sm text-red-500">{templateError}</p>}
          <div className="flex gap-3 justify-end">
            <Button variant="ghost" onClick={() => setShowSaveTemplate(false)}>
              Cancel
            </Button>
            <Button onClick={handleSaveTemplate} disabled={templateActionLoading || !templateName.trim()}>
              {templateActionLoading ? <Spinner /> : <BookmarkPlus size={16} />}
              {templateActionLoading ? 'Saving...' : 'Save Template'}
            </Button>
          </div>
        </div>
      </Modal>

      {/* Templates list modal */}
      <Modal isOpen={showTemplates} onClose={() => setShowTemplates(false)} title="Meal Plan Templates" size="md">
        <div className="space-y-3">
          {templateError && <p className="text-sm text-red-500">{templateError}</p>}
          {templatesLoading ? (
            <div className="flex justify-center py-8">
              <Spinner />
            </div>
          ) : templates.length === 0 ? (
            <div className="text-center py-8">
              <Bookmark size={32} className="mx-auto text-warm-gray/40 mb-2" />
              <p className="text-warm-gray">No saved templates yet</p>
            </div>
          ) : (
            <div className="space-y-2 max-h-96 overflow-y-auto">
              {templates.map(template => (
                <div key={template.id} className="flex items-center justify-between gap-3 p-3 rounded-xl bg-cream">
                  <div className="min-w-0">
                    <p className="font-medium text-brown truncate">{template.name}</p>
                    <p className="text-xs text-warm-gray">{template.item_count} meal{template.item_count === 1 ? '' : 's'}</p>
                  </div>
                  <div className="flex items-center gap-1 shrink-0">
                    <Button
                      variant="secondary"
                      onClick={() => handleApplyTemplate(template.id)}
                      disabled={templateActionLoading}
                    >
                      Apply to This Week
                    </Button>
                    <button
                      onClick={() => handleDeleteTemplate(template.id)}
                      disabled={templateActionLoading}
                      className="p-2 rounded-lg text-warm-gray hover:text-red-500 hover:bg-red-50 transition-colors duration-200 min-w-[40px] min-h-[40px] flex items-center justify-center"
                      aria-label={`Delete ${template.name}`}
                    >
                      <Trash2 size={16} />
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </Modal>
    </div>
  );
}
