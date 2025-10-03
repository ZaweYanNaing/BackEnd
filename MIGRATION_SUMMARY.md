# Database Table Rename Migration Summary

## Overview
This migration renames the database tables from generic "categories" to more specific "dietary_preferences" to better reflect their purpose in the FoodFusion application.

## Database Changes

### Tables Renamed:
1. `categories` → `dietary_preferences`
2. `recipe_categories` → `recipe_dietary_preferences`

### Migration Script:
- **File**: `database/migrate_rename_tables.sql`
- **Purpose**: Safely rename tables without data loss
- **Status**: Ready to execute

## Code Changes Made

### 1. Database Functions (`includes/functions.php`)
- Updated all SQL queries to use new table names
- Changed `getCategories()` function to query `dietary_preferences` table
- Updated all JOIN statements and subqueries
- Modified INSERT, UPDATE, and DELETE operations

### 2. Page Updates

#### `pages/recipes.php`
- Updated filter dropdown label from "Category" to "Dietary Preference"
- Changed "All Categories" to "All Preferences"
- Updated comments and variable descriptions
- Changed styling for dietary preference tags (green instead of gray)

#### `pages/create-recipe.php`
- Updated section heading from "Categories & Cuisine" to "Dietary Preferences & Cuisine"
- Changed form labels and validation messages
- Updated JavaScript validation messages

#### `pages/edit-recipe.php`
- Updated section heading to "Dietary Preferences"
- Changed form labels and validation messages
- Updated database query for current recipe preferences
- Updated JavaScript validation messages

#### `pages/search.php`
- Updated filter dropdown label to "Dietary Preference"
- Changed "All Categories" to "All Preferences"

### 3. Database Schema (`database/schema.sql`)
- Updated table creation statements
- Updated sample data insertion statements
- Updated foreign key references
- Updated comments and documentation

### 4. Documentation (`README.md`)
- Updated feature descriptions
- Updated database table list

## User-Facing Changes

### Before:
- "Categories" dropdown in filters
- "All Categories" option
- "Categories & Cuisine" section in forms
- Gray category tags

### After:
- "Dietary Preference" dropdown in filters
- "All Preferences" option
- "Dietary Preferences & Cuisine" section in forms
- Green dietary preference tags

## Migration Steps

1. **Backup your database** before running the migration
2. Execute `database/migrate_rename_tables.sql` on your database
3. Deploy the updated code files
4. Test the application functionality

## Files Modified

### Database:
- `database/schema.sql`
- `database/migrate_rename_tables.sql` (new)

### Backend:
- `includes/functions.php`

### Frontend Pages:
- `pages/recipes.php`
- `pages/create-recipe.php`
- `pages/edit-recipe.php`
- `pages/search.php`

### Documentation:
- `README.md`
- `MIGRATION_SUMMARY.md` (new)

## Testing Checklist

After migration, verify:
- [ ] Recipe listing page loads correctly
- [ ] Dietary preference filters work
- [ ] Recipe creation with dietary preferences works
- [ ] Recipe editing preserves dietary preferences
- [ ] Search by dietary preference functions
- [ ] Existing recipes display their dietary preferences correctly

## Rollback Plan

If issues occur, you can rollback by:
1. Renaming tables back: `dietary_preferences` → `categories`, `recipe_dietary_preferences` → `recipe_categories`
2. Reverting the code changes to use the original table names

## Notes

- All existing data is preserved during the migration
- Foreign key constraints are automatically updated
- The internal variable names in PHP code still use `$categories` for backward compatibility
- The database column names remain the same (only table names changed)