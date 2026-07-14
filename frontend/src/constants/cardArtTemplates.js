// Mirrors CardArtTemplates::TEMPLATES in api/services/CardArtTemplates.php
export const CARD_ART_TEMPLATES = [
  { key: 'gold-luxury', label: 'Gold Luxury', description: 'Dark, gold-foil editorial card' },
  { key: 'editorial-storyboard', label: 'Editorial Storyboard', description: 'Magazine-style narrative layout' },
  { key: 'boho-scrapbook', label: 'Boho Scrapbook', description: 'Hand-lettered, watercolor scrapbook' },
  { key: 'wellness-editorial', label: 'Wellness Editorial', description: 'Bright, airy morning-light poster' },
  { key: 'anatomy-diagram', label: 'Anatomy Diagram', description: 'Illustrated ingredient callout poster' },
  { key: 'vintage-cookbook', label: 'Vintage Cookbook', description: 'Two-page aged cookbook spread' },
  { key: 'dynamic-infographic', label: 'Dynamic Infographic', description: 'Social-feed-style infographic' },
  { key: 'lifestyle-sidebar', label: 'Lifestyle Sidebar', description: 'Hero photo with sidebar recipe list' },
  { key: 'modern-minimal', label: 'Modern Minimal', description: 'Scandinavian minimal editorial card' },
];

export function cardArtTemplateLabel(key) {
  return CARD_ART_TEMPLATES.find((t) => t.key === key)?.label || key;
}
