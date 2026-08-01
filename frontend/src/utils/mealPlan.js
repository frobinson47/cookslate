import { Sunrise, Sun, Moon, Cookie } from 'lucide-react';

export const DAY_NAMES = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
export const DAY_NAMES_FULL = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
export const MEAL_TYPES = [
  { value: 'breakfast', label: 'Breakfast', Icon: Sunrise },
  { value: 'lunch', label: 'Lunch', Icon: Sun },
  { value: 'dinner', label: 'Dinner', Icon: Moon },
  { value: 'snack', label: 'Snack', Icon: Cookie },
];

export function getDayDate(weekStartStr, dayIndex) {
  const d = new Date(weekStartStr + 'T00:00:00');
  d.setDate(d.getDate() + dayIndex);
  return d;
}
