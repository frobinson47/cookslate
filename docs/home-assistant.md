# Home Assistant Integration

Cookslate exposes two read-only endpoints for pulling data into Home
Assistant via a `rest` sensor — today's meal plan and pantry items that are
about to expire. There's no official HACS integration; this is a documented
REST sensor configuration you copy into `configuration.yaml`.

## Prerequisites

- A Cookslate instance reachable from your Home Assistant host
- The instance-wide read-only API key, set via the `COOKSLATE_API_KEY`
  environment variable on the Cookslate server (see the main README for
  where this is configured)
- The `today-meal` endpoint additionally requires an active Pro license
  (meal planning is a Pro feature) — `pantry-alerts` works on the free tier

> **Note:** the API key is instance-wide, not per-user. If your household
> has more than one admin account, these endpoints reflect the
> lowest-numbered admin user's data, not any particular household member's.

## `configuration.yaml`

```yaml
rest:
  - resource: "https://your-cookslate-instance/api/external/today-meal?apikey=YOUR_API_KEY"
    scan_interval: 3600
    sensor:
      - name: "Cookslate Today's Meals"
        value_template: "{{ value_json.meals | length }}"
        json_attributes_path: "$"
        json_attributes:
          - meals

  - resource: "https://your-cookslate-instance/api/external/pantry-alerts?apikey=YOUR_API_KEY&days=3"
    scan_interval: 3600
    sensor:
      - name: "Cookslate Pantry Alerts"
        value_template: "{{ value_json.items | length }}"
        json_attributes_path: "$"
        json_attributes:
          - items
```

Adjust `days` on the pantry-alerts URL to change the expiration window
(default 3 days if omitted).

## Example Lovelace card

A simple markdown card showing today's meals and any expiring pantry items:

```yaml
type: markdown
title: Cookslate
content: |
  {% if state_attr('sensor.cookslate_today_s_meals', 'meals') %}
  **Tonight's Dinner**
  {% for meal in state_attr('sensor.cookslate_today_s_meals', 'meals') %}
  - {{ meal.recipe.title }}
  {% endfor %}
  {% else %}
  No meals planned today.
  {% endif %}

  {% if state_attr('sensor.cookslate_pantry_alerts', 'items') %}
  **Use It Soon**
  {% for item in state_attr('sensor.cookslate_pantry_alerts', 'items') %}
  - {{ item.ingredient_name }} (exp. {{ item.expiration_date }})
  {% endfor %}
  {% endif %}
```

## Endpoint reference

### `GET /api/external/today-meal?apikey=...`

Requires an active Pro license. Returns:

```json
{ "meals": [ { "id": 1, "recipe": { "id": 12, "title": "..." }, "day_of_week": 3 } ] }
```

### `GET /api/external/pantry-alerts?apikey=...&days=3`

Returns pantry items expiring within `days` (default 3), soonest first:

```json
{ "items": [ { "id": 4, "ingredient_name": "milk", "expiration_date": "2026-08-02" } ] }
```
