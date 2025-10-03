# Events Image Upload Feature Implementation

## Overview
Added image upload functionality to the events system, allowing users to upload optional images when creating events. Images are displayed in the events carousel, with fallback icons when no image is provided.

## Database Changes

### 1. Updated Events Table Schema
- **File**: `database/add_events_table.sql`
- **Change**: Added `image_url VARCHAR(500) NULL` column to the events table
- **Purpose**: Store the filename of uploaded event images

### 2. Migration Script
- **File**: `database/add_events_image_column.sql` (new)
- **Purpose**: Add image_url column to existing events tables
- **Usage**: Run this script if you already have an events table without the image_url column

## Backend Changes

### 1. Event Creation API (`api/event_create.php`)
**Added Features:**
- Image upload validation (file type and size)
- Supported formats: JPEG, PNG, GIF, WebP
- Maximum file size: 5MB
- Unique filename generation with user ID and timestamp
- Automatic uploads directory creation
- Updated database insertion to include image_url

**New Validation:**
- File type validation using `mime_content_type()`
- File size validation (5MB limit)
- Proper error handling for upload failures

### 2. Event List API (`api/event_list.php`)
- No changes needed - already returns all columns including image_url

## Frontend Changes

### 1. Create Event Form (`pages/home.php`)
**Added Components:**
- Image upload input field with file type restrictions
- Image preview functionality with placeholder
- Form validation for image files
- Updated form to use `enctype="multipart/form-data"`

**New Form Fields:**
```html
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Event Image (Optional)</label>
    <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
            <img id="event-image-preview" 
                 src="https://via.placeholder.com/120x80/e5e7eb/6b7280?text=No+Image" 
                 alt="Event preview" class="w-30 h-20 object-cover rounded-lg border border-gray-300">
        </div>
        <div class="flex-1">
            <input type="file" id="eventImage" name="event_image" accept="image/*">
            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF, WebP up to 5MB</p>
        </div>
    </div>
</div>
```

### 2. JavaScript Updates
**Enhanced Form Submission:**
- Changed from JSON to FormData for file upload support
- Added image preview functionality with file validation
- Client-side file type and size validation
- Form reset functionality when modal is closed

**New JavaScript Features:**
- Real-time image preview
- File validation (type and size)
- FormData handling for multipart form submission
- Enhanced error handling

### 3. Events Carousel Display
**Updated Event Display:**
- Modified database queries to include `image_url`
- Added conditional image display logic
- Shows uploaded image when available
- Falls back to calendar icon when no image is provided

**Display Logic:**
```php
<?php if (!empty($event['image_url'])): ?>
    <img src="uploads/<?php echo htmlspecialchars($event['image_url']); ?>" 
         alt="<?php echo htmlspecialchars($event['title']); ?>" 
         class="w-full h-full object-cover">
<?php else: ?>
    <div class="w-full h-full flex items-center justify-center">
        <div class="text-center">
            <i class="fas fa-calendar-alt text-6xl text-green-600 mb-2"></i>
            <div class="text-gray-500">Community Cooking Event</div>
        </div>
    </div>
<?php endif; ?>
```

## File Structure
```
├── database/
│   ├── add_events_table.sql (updated)
│   └── add_events_image_column.sql (new)
├── api/
│   ├── event_create.php (updated)
│   └── event_list.php (no changes needed)
├── pages/
│   └── home.php (updated)
└── uploads/ (auto-created for image storage)
```

## Features Implemented

### ✅ Image Upload
- Optional image upload in create event form
- File type validation (JPEG, PNG, GIF, WebP)
- File size validation (5MB maximum)
- Unique filename generation
- Secure file handling

### ✅ Image Display
- Event images displayed in carousel
- Responsive image sizing
- Fallback icon when no image provided
- Proper image alt text for accessibility

### ✅ User Experience
- Real-time image preview
- Client-side validation
- Clear error messages
- Form reset on modal close
- Smooth integration with existing UI

### ✅ Security
- File type validation
- File size limits
- Unique filename generation
- Proper error handling
- SQL injection prevention

## Usage Instructions

1. **Database Setup**: Run the migration script if you have existing events table
2. **Create Event**: Users can now optionally upload images when creating events
3. **View Events**: Images are automatically displayed in the events carousel
4. **Fallback**: Events without images show a calendar icon

## Technical Notes

- Images are stored in the `uploads/` directory
- Filenames include user ID and timestamp for uniqueness
- Database stores only the filename, not the full path
- Client-side and server-side validation for security
- Responsive design maintains existing UI consistency