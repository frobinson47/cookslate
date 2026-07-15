ALTER TABLE recipe_card_art
    ADD COLUMN source ENUM('generated', 'uploaded') NOT NULL DEFAULT 'generated' AFTER template;
