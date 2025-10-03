# FoodFusion - PHP Version

A comprehensive culinary platform built with PHP, MySQL, and TailwindCSS. This is a pure PHP implementation of the FoodFusion recipe sharing platform, designed to promote home cooking and culinary creativity.

## Features

### 🍳 Recipe Management
- **Create, Read, Update, Delete** recipes
- **Rich recipe details** including ingredients, instructions, cooking time, difficulty level
- **Image upload** support for recipe photos
- **Categorization** by dietary preferences and cuisine types
- **Search and filtering** by category, difficulty, cooking time
- **Recipe ratings and reviews** system
- **PDF recipe card downloads** with professional formatting

### 👥 User Management
- **User registration and authentication**
- **Profile management** with bio, location, website
- **Profile image upload**
- **User activity tracking**

### ❤️ Social Features
- **Like and favorite** recipes
- **Recipe reviews and ratings** (1-5 stars)
- **Recipe views tracking**
- **User favorites** collection

### 💡 Cooking Tips
- **Share cooking tips** and techniques
- **Browse community tips**
- **Search tips** by content
- **Download cooking tips as PDF**

### 📚 Culinary Resources
- **Video Tutorials** - Watch and download recipe videos
- **Educational Videos** - Downloadable instructional content
- **Kitchen Hacks** - Practical cooking tips and shortcuts
- **Recipe Cards** - Beautiful PDF downloads with images

### 🔍 Search & Discovery
- **Advanced search** across recipes and cooking tips
- **Filter by category, difficulty, cooking time**
- **Trending and popular** content discovery

### 🎨 Modern UI
- **Responsive design** with TailwindCSS
- **Mobile-friendly** interface
- **Clean and intuitive** user experience
- **Toast notifications** for user feedback
- **Professional video player** with fullscreen modal

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: TailwindCSS 4.1.13
- **Icons**: Font Awesome 6.0
- **Server**: Apache (XAMPP)
- **PDF Generation**: TCPDF Library
- **Package Management**: Composer

## Installation

### Prerequisites
- XAMPP or similar local development environment
- PHP 7.4 or higher
- MySQL 8.0 or higher
- Composer (for PDF generation dependencies)
- Node.js (for TailwindCSS compilation)
- Web browser

### Setup Instructions

1. **Clone/Download** the project to your XAMPP htdocs directory:
   ```
   /Applications/XAMPP/xamppfiles/htdocs/BEfood/
   ```

2. **Install Dependencies**:
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install Node.js dependencies
   npm install
   ```

3. **Start XAMPP** services:
   - Start Apache
   - Start MySQL

4. **Create Database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `foodfusion_db`
   - Import the schema from `database/schema.sql`
   - Optionally import `database/culinary_resources_schema.sql` for additional features

5. **Configure Database** (if needed):
   - Edit `config/database.php` to match your MySQL credentials
   - Default settings work with XAMPP default configuration

6. **Compile TailwindCSS** (if making style changes):
   ```bash
   npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch
   ```

7. **Set Permissions**:
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/educational-resources/
   ```

8. **Access the Application**:
   - Open your browser and go to `http://localhost/BEfood/`

## Project Structure

```
BEfood/
├── config/
│   └── database.php          # Database configuration
├── database/
│   ├── schema.sql            # Main database schema and sample data
│   └── culinary_resources_schema.sql # Additional culinary resources schema
├── includes/
│   ├── functions.php         # Core PHP functions
│   ├── header.php           # HTML header template
│   └── footer.php           # HTML footer template
├── pages/
│   ├── home.php             # Homepage
│   ├── login_process.php    # Login API endpoint
│   ├── register_process.php # Registration API endpoint
│   ├── profile.php          # User profile
│   ├── edit-profile.php     # Edit profile
│   ├── recipes.php          # Recipe listing
│   ├── recipe-detail.php    # Individual recipe view
│   ├── create-recipe.php    # Create new recipe
│   ├── culinary.php         # Culinary resources hub
│   ├── resources.php        # Educational resources
│   ├── search.php           # Search functionality
│   ├── favorites.php        # User favorites
│   ├── logout.php           # Logout handler
│   └── 404.php              # 404 error page
├── src/
│   ├── input.css            # TailwindCSS input
│   └── output.css           # Compiled TailwindCSS
├── uploads/                 # User uploaded images
│   └── educational-resources/ # Educational video files
├── vendor/                  # Composer dependencies
├── node_modules/           # Node.js dependencies
├── composer.json           # PHP dependencies
├── composer.lock           # PHP dependency lock file
├── package.json            # Node.js dependencies
├── package-lock.json       # Node.js dependency lock file
├── .gitignore              # Git ignore rules
├── index.php               # Main application entry point
├── upload.php              # Image upload handler
└── README.md               # This file
```

## Database Schema

The application uses the following main tables:
- `users` - User accounts and profiles
- `recipes` - Recipe information
- `dietary_preferences` - Recipe dietary preferences
- `cuisine_types` - Cuisine types
- `ingredients` - Ingredient database
- `recipe_ingredients` - Recipe-ingredient relationships
- `recipe_likes` - User recipe likes
- `recipe_reviews` - Recipe reviews and ratings
- `user_favorites` - User favorite recipes
- `cooking_tips` - Community cooking tips
- `recipe_views` - Recipe view tracking

## Usage

### For Users
1. **Register** a new account or **login** with existing credentials
2. **Browse recipes** by category, difficulty, or search terms
3. **Create recipes** with detailed ingredients and instructions
4. **Like and favorite** recipes you enjoy
5. **Rate and review** recipes
6. **Share cooking tips** with the community
7. **Manage your profile** and view your activity

### For Developers
- The code follows a simple MVC-like pattern
- All database operations are in `includes/functions.php`
- Frontend uses TailwindCSS for styling
- JavaScript is used for interactive features
- Image uploads are handled securely with validation

## Security Features

- **Password hashing** using PHP's `password_hash()`
- **Input sanitization** and validation
- **SQL injection prevention** with prepared statements
- **File upload validation** (type and size)
- **Session management** for user authentication
- **CSRF protection** through form validation

## Customization

### Styling
- Modify `src/input.css` and run TailwindCSS build
- Update `includes/header.php` for global styling changes
- Customize colors in the TailwindCSS configuration

### Functionality
- Add new features by creating pages in the `pages/` directory
- Extend database functionality in `includes/functions.php`
- Modify the routing logic in `index.php`

## Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check MySQL is running
   - Verify database credentials in `config/database.php`
   - Ensure database `foodfusion_db` exists

2. **Image Upload Issues**:
   - Check `uploads/` directory permissions
   - Verify file size limits in PHP configuration
   - Ensure proper file type validation

3. **Page Not Found (404)**:
   - Check URL routing in `index.php`
   - Verify page files exist in `pages/` directory

4. **Styling Issues**:
   - Ensure `src/output.css` is properly linked
   - Check TailwindCSS compilation

## Contributing

This is a learning project. Feel free to:
- Add new features
- Improve the UI/UX
- Optimize database queries
- Add more security features
- Enhance the search functionality

## License

This project is for educational purposes. Feel free to use and modify as needed.

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the code comments
3. Check PHP and MySQL error logs
4. Verify XAMPP configuration

---

**Happy Cooking! 🍳👨‍🍳👩‍🍳**
